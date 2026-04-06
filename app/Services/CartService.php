<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\Cart\CartException;
use App\Exceptions\Cart\InsufficientStockException;
use App\Exceptions\Cart\InvalidQuantityException;
use App\Exceptions\Cart\ProductNotAvailableException;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Traits\CartCalculationTrait;
use App\Traits\CartStorageTrait;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CartService
{
    use CartCalculationTrait;
    use CartStorageTrait;

    protected string $sessionKey;

    protected string $instanceName;

    protected array $config;

    protected static array $instances = [];

    public function __construct(string $instanceName = 'default')
    {
        $this->instanceName = $instanceName;
        $this->sessionKey = 'cart_' . $instanceName;
        $this->config = config('cart', []);

        // Configure storage based on instance context
        $this->configureStorageForInstance($instanceName);
    }

    /** Get or create a cart instance */
    public static function instance(string $instanceName = 'default'): self
    {
        if (! isset(static::$instances[$instanceName])) {
            static::$instances[$instanceName] = new static($instanceName);
        }

        return static::$instances[$instanceName];
    }

    /** Configure storage strategy based on instance context */
    protected function configureStorageForInstance(string $instanceName): void
    {
        switch (strtolower($instanceName)) {
            case 'pos':
            case 'sale':
                // POS needs fast access, use session + cache
                $this->setPrimaryStorage('session')
                    ->setSecondaryStorage('cache')
                    ->setHybridStorage(true)
                    ->setCacheDuration(60); // 1 hour for POS sessions

                break;

            case 'purchase':
            case 'purchases':
                // Purchases might need longer persistence
                $this->setPrimaryStorage('session')
                    ->setSecondaryStorage('cache')
                    ->setHybridStorage(true)
                    ->setCacheDuration(480); // 8 hours for purchase sessions

                break;

            case 'quote':
            case 'quotation':
                // Quotes need longer persistence, use cache + database
                $this->setPrimaryStorage('cache')
                    ->setSecondaryStorage('database')
                    ->setHybridStorage(true)
                    ->setCacheDuration(1440); // 24 hours

                break;

            default:
                // Default: session + cache hybrid
                $this->setPrimaryStorage('session')
                    ->setSecondaryStorage('cache')
                    ->setHybridStorage(true)
                    ->setCacheDuration(720); // 12 hours

                break;
        }
    }

    /** Configure storage for Livewire components */
    public function configureForLivewire(string $componentType = 'default'): self
    {
        // Livewire components benefit from session storage for reactivity
        // with cache backup for persistence across requests
        $this->setPrimaryStorage('session')
            ->setSecondaryStorage('cache')
            ->setHybridStorage(true);

        // Adjust cache duration based on component type
        switch (strtolower($componentType)) {
            case 'pos':
                $this->setCacheDuration(60); // Short duration for POS

                break;
            case 'sales':
            case 'purchases':
                $this->setCacheDuration(240); // 4 hours for sales/purchases

                break;
            default:
                $this->setCacheDuration(120); // 2 hours default

                break;
        }

        return $this;
    }

    /** Configure storage for background jobs */
    public function configureForJobs(): self
    {
        // Jobs should use cache for temporary storage
        $this->setPrimaryStorage('cache')
            ->setSecondaryStorage('database')
            ->setHybridStorage(false) // Single storage for jobs
            ->setCacheDuration(30); // Short duration, jobs should be quick

        return $this;
    }

    /** Add an item to the cart with validation */
    public function add(array $item): string
    {
        // Validate the item before adding
        $this->validateCartItem($item);

        $rowId = $this->generateRowId($item);
        $cartItem = $this->createCartItem($item, $rowId);
        $content = $this->getContent();

        // Calculate final quantity (existing + new)
        $finalQuantity = $cartItem['quantity'];

        if ($content->has($rowId)) {
            $existingItem = $content->get($rowId);
            $finalQuantity += $existingItem['quantity'];
        }

        // Validate stock availability for final quantity
        if (isset($item['warehouse_id'])) {
            $this->validateStock($item['id'], $item['warehouse_id'], $finalQuantity);
        }

        // Update quantity if item exists
        if ($content->has($rowId)) {
            $cartItem['quantity'] = $finalQuantity;
        }

        $content->put($rowId, $cartItem);
        $this->storeContent($content);

        // Log cart operation
        $this->logCartOperation('add', $item['id'], $cartItem['quantity'], [
            'row_id' => $rowId,
            'product_name' => $item['name'] ?? 'Unknown',
            'price' => $cartItem['price'],
        ]);

        return $rowId;
    }

    /** Update an item in the cart with validation */
    public function update(string $rowId, array $data): bool
    {
        $content = $this->getContent();

        if (! $content->has($rowId)) {
            Log::warning('Attempted to update non-existent cart item', ['row_id' => $rowId]);

            return false;
        }

        $item = $content->get($rowId);
        $originalQuantity = $item['quantity'];

        // Validate quantity if being updated
        if (isset($data['quantity'])) {
            $this->validateQuantity($data['quantity']);

            // Validate stock if warehouse info is available
            if (isset($item['attributes']['warehouse_id'])) {
                $this->validateStock($item['id'], $item['attributes']['warehouse_id'], $data['quantity']);
            }
        }

        // Update allowed fields
        foreach ($data as $key => $value) {
            if (in_array($key, ['quantity', 'price', 'name', 'attributes'])) {
                $item[$key] = $value;
            }
        }

        $content->put($rowId, $item);
        $this->storeContent($content);

        // Log cart operation
        $this->logCartOperation('update', $item['id'], $item['quantity'], [
            'row_id' => $rowId,
            'original_quantity' => $originalQuantity,
            'updated_fields' => array_keys($data),
        ]);

        return true;
    }

    /** Remove an item from the cart */
    public function remove(string $rowId): bool
    {
        $content = $this->getContent();

        if (! $content->has($rowId)) {
            Log::warning('Attempted to remove non-existent cart item', ['row_id' => $rowId]);

            return false;
        }

        $item = $content->get($rowId);
        $content->forget($rowId);
        $this->storeContent($content);

        // Log cart operation
        $this->logCartOperation('remove', $item['id'], $item['quantity'], [
            'row_id' => $rowId,
            'product_name' => $item['name'] ?? 'Unknown',
        ]);

        return true;
    }

    /** Get cart content */
    public function getContent(): Collection
    {
        return $this->retrieveContent();
    }

    /** Get a specific item from the cart */
    public function get(string $rowId): ?array
    {
        $content = $this->getContent();

        return $content->get($rowId);
    }

    /** Clear the entire cart */
    public function clear(): void
    {
        $this->storeContent(collect());
    }

    /** Destroy the cart (alias for clear) */
    public function destroy(): void
    {
        $this->clear();
    }

    /** Get cart content (alias for getContent) */
    public function content(): Collection
    {
        return $this->getContent()->filter(function ($item, $rowId) {
            // Filter out global settings (they are not arrays)
            return is_array($item) && isset($item['id']);
        })->map(function ($item, $rowId) {
            return new CartItem($item, $rowId);
        });
    }

    /** Get cart subtotal (alias for getSubTotal) */
    public function subtotal(): float
    {
        return $this->getSubTotal();
    }

    /** Get cart total (alias for getTotal) */
    public function total(): float
    {
        return $this->getTotal();
    }

    /** Get tax amount (alias for getTax) */
    public function tax(): float
    {
        return $this->getTax();
    }

    /** Get discount amount */
    public function discount(): float
    {
        return $this->calculateDiscount($this->getContent());
    }

    /** Get cart count */
    public function count(): float|int
    {
        return $this->getContent()->sum('quantity');
    }

    /** Check if cart is empty */
    public function isEmpty(): bool
    {
        return $this->getContent()->isEmpty();
    }

    /** Get cart subtotal */
    public function getSubTotal(): float
    {
        return $this->calculateSubTotal($this->getContent());
    }

    /** Get cart total with tax */
    public function getTotal(): float
    {
        return $this->calculateTotal($this->getContent());
    }

    /** Get tax amount */
    public function getTax(): float
    {
        return $this->calculateTax($this->getContent());
    }

    /** Search for items in the cart */
    public function search(callable $callback): Collection
    {
        return $this->getContent()->filter(function ($item, $rowId) {
            // Filter out global settings (they are not arrays)
            return is_array($item) && isset($item['id']);
        })->map(function ($item, $rowId) {
            return new CartItem($item, $rowId);
        })->filter($callback);
    }

    /** Associate a model with cart items */
    public function associate(string $rowId, string $model): bool
    {
        $content = $this->getContent();

        if (! $content->has($rowId)) {
            return false;
        }

        $item = $content->get($rowId);
        $item['associated_model'] = $model;

        $content->put($rowId, $item);
        $this->storeContent($content);

        return true;
    }

    /** Set cart instance for specific user */
    public function session(string $sessionId): self
    {
        $this->sessionKey = 'cart_' . $this->instanceName . '_' . $sessionId;

        return $this;
    }

    /** Create a cart item array */
    protected function createCartItem(array $item, string $rowId): array
    {
        return [
            'rowId' => $rowId,
            'id' => $item['id'],
            'name' => $item['name'],
            'price' => (float) $item['price'],
            'quantity' => $item['quantity'] ?? 0,
            'attributes' => $item['attributes'] ?? [],
            'associated_model' => $item['associated_model'] ?? null,
            'conditions' => $item['conditions'] ?? [],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /** Generate a unique row ID for the cart item */
    protected function generateRowId(array $item): string
    {
        $attributes = $item['attributes'] ?? [];
        ksort($attributes);

        return md5($item['id'] . serialize($attributes));
    }

    /** Get the session key */
    public function getSessionKey(): string
    {
        return $this->sessionKey;
    }

    /** Get the instance name */
    public function getInstanceName(): string
    {
        return $this->instanceName;
    }

    /** Set global tax percentage */
    public function setGlobalTax(float $taxPercentage): self
    {
        $content = $this->getContent();
        $content['global_tax'] = $taxPercentage;
        $this->storeContent($content);

        return $this;
    }

    /** Get global tax percentage */
    public function getGlobalTax(): float
    {
        $content = $this->getContent();

        return $content['global_tax'] ?? 0.0;
    }

    /** Set global discount percentage */
    public function setGlobalDiscount(float $discountPercentage): self
    {
        $content = $this->getContent();
        $content['global_discount'] = $discountPercentage;
        $this->storeContent($content);

        return $this;
    }

    /** Get global discount percentage */
    public function getGlobalDiscount(): float
    {
        $content = $this->getContent();

        return $content['global_discount'] ?? 0.0;
    }

    /** Validate cart item data */
    protected function validateCartItem(array $item): void
    {
        // Required fields validation
        $requiredFields = ['id', 'name', 'price'];

        foreach ($requiredFields as $field) {
            if (! isset($item[$field])) {
                throw new CartException("Missing required field: {$field}", 400);
            }
        }

        // Validate quantity
        // $this->validateQuantity($item['quantity']);

        // Validate product exists and is available
        // $this->validateProductAvailability($item['id']);

        // Validate price
        if (! is_numeric($item['price']) || $item['price'] < 0) {
            throw new CartException('Price must be a positive number', 400);
        }
    }

    /** Validate quantity */
    protected function validateQuantity($quantity): void
    {
        if ($quantity <= 0) {
            throw new InvalidQuantityException($quantity, 'Quantity must be greater than zero');
        }

        if ($quantity > 10000) {
            throw new InvalidQuantityException($quantity, 'Quantity cannot exceed 10,000 items');
        }
    }

    /** Validate product availability */
    protected function validateProductAvailability(int $productId): void
    {
        $product = Product::find($productId);

        if (! $product) {
            throw new ProductNotAvailableException($productId, 'Product not found');
        }

        if ($product->status === false || $product->status === 0) {
            throw new ProductNotAvailableException($productId, 'Product is not active');
        }

        if (isset($product->availability) && $product->availability === 'unavailable') {
            throw new ProductNotAvailableException($productId, 'Product is marked as unavailable');
        }
    }

    /** Validate stock availability */
    protected function validateStock(int $productId, int $warehouseId, int $requestedQuantity): void
    {
        $productWarehouse = ProductWarehouse::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->first();

        if (! $productWarehouse) {
            throw new InsufficientStockException($productId, $warehouseId, $requestedQuantity, 0);
        }

        if ($productWarehouse->qty < $requestedQuantity) {
            throw new InsufficientStockException(
                $productId,
                $warehouseId,
                $requestedQuantity,
                $productWarehouse->qty
            );
        }
    }

    /** Log cart operations */
    protected function logCartOperation(string $operation, $productId, $quantity, array $context = []): void
    {
        Log::info('Cart operation performed', array_merge([
            'operation' => $operation,
            'instance' => $this->instanceName,
            'product_id' => $productId,
            'quantity' => $quantity,
            'session_key' => $this->sessionKey,
            'timestamp' => now()->toISOString(),
        ], $context));
    }

    /** Clean up expired carts */
    public function cleanupExpiredCarts(): int
    {
        $cleaned = 0;

        try {
            // This would depend on your storage implementation
            // For now, we'll just log the cleanup attempt
            Log::info('Cart cleanup initiated', [
                'instance' => $this->instanceName,
                'timestamp' => now()->toISOString(),
            ]);

            // TODO: Implement actual cleanup logic based on storage type
        } catch (Exception $e) {
            Log::error('Cart cleanup failed', [
                'instance' => $this->instanceName,
                'error' => $e->getMessage(),
            ]);
        }

        return $cleaned;
    }

    /** Get cart statistics */
    public function getCartStatistics(): array
    {
        $content = $this->getContent();
        $items = $content->filter(fn ($item) => is_array($item) && isset($item['id']));

        return [
            'total_items' => $items->count(),
            'total_quantity' => $items->sum('quantity'),
            'unique_products' => $items->pluck('id')->unique()->count(),
            'subtotal' => $this->getSubTotal(),
            'tax' => $this->getTax(),
            'total' => $this->getTotal(),
            'instance' => $this->instanceName,
            'last_updated' => $items->max('updated_at'),
        ];
    }
}
