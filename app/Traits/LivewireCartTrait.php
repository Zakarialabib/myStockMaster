<?php

declare(strict_types=1);

namespace App\Traits;

use App\Services\CartService;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\On;

trait LivewireCartTrait
{
    protected ?CartService $cart = null;
    public string $cartInstance = 'default';
    public array $cartConfig = [];

    /** Initialize cart for Livewire component */
    public function initializeCart(string $instance = null, array $config = []): void
    {
        $this->cartInstance = $instance ?? $this->cartInstance;
        $this->cartConfig = array_merge($this->cartConfig, $config);

        // Create cart service instance
        $this->cart = App::make(CartService::class, ['instanceName' => $this->cartInstance]);

        // Configure for Livewire
        $this->cart->configureForLivewire($this->getComponentType());

        // Apply any custom configuration
        $this->applyCartConfiguration();
    }

    /** Boot hook - called before mount and hydrate */
    public function bootLivewireCartTrait(): void
    {
        if ($this->cart === null) {
            // Auto-detect cart instance based on component type
            $componentType = $this->getComponentType();
            $this->initializeCart($componentType);
        }
    }

    /** Mount hook - initialize cart */
    public function mountLivewireCartTrait(): void
    {
        if ($this->cart === null) {
            $this->initializeCart();
        }
    }

    /** Hydrate hook - restore cart state */
    public function hydrateLivewireCartTrait(): void
    {
        if ($this->cart === null) {
            $this->initializeCart();
        }

        // Sync cart state with component properties
        $this->syncCartState();
    }

    /** Dehydrate hook - persist cart state */
    public function dehydrateLivewireCartTrait(): void
    {
        if (isset($this->cart)) {
            // Ensure cart is persisted before component dehydration
            $this->persistCartState();
        }
    }

    /** Get component type for cart configuration */
    protected function getComponentType(): string
    {
        $className = class_basename(static::class);

        if (str_contains(strtolower($className), 'pos')) {
            return 'pos';
        }

        if (str_contains(strtolower($className), 'sale')) {
            return 'sales';
        }

        if (str_contains(strtolower($className), 'purchase')) {
            return 'purchases';
        }

        return 'default';
    }

    /** Apply custom cart configuration */
    protected function applyCartConfiguration(): void
    {
        if (isset($this->cartConfig['primary_storage'])) {
            $this->cart->setPrimaryStorage($this->cartConfig['primary_storage']);
        }

        if (isset($this->cartConfig['secondary_storage'])) {
            $this->cart->setSecondaryStorage($this->cartConfig['secondary_storage']);
        }

        if (isset($this->cartConfig['hybrid_storage'])) {
            $this->cart->setHybridStorage($this->cartConfig['hybrid_storage']);
        }

        if (isset($this->cartConfig['cache_duration'])) {
            $this->cart->setCacheDuration($this->cartConfig['cache_duration']);
        }
    }

    /** Sync cart state with component properties */
    protected function syncCartState(): void
    {
        // Override in components to sync specific properties
        // Example: $this->cartTotal = $this->cart->total();
    }

    /** Persist cart state */
    protected function persistCartState(): void
    {
        // Cart is automatically persisted through storage trait
        // Override if additional persistence logic is needed
    }

    /** Add item to cart with Livewire reactivity */
    public function addToCart(array $item): string
    {
        $rowId = $this->cart->add($item);

        // Trigger cart updated event
        $this->dispatch('cart-updated', [
            'instance' => $this->cartInstance,
            'action'   => 'add',
            'rowId'    => $rowId,
            'item'     => $item,
        ]);

        // Sync component state
        $this->syncCartState();

        return $rowId;
    }

    /** Update cart item with Livewire reactivity */
    public function updateCartItem(string $rowId, array $data): void
    {
        $this->cart->update($rowId, $data);

        // Trigger cart updated event
        $this->dispatch('cart-updated', [
            'instance' => $this->cartInstance,
            'action'   => 'update',
            'rowId'    => $rowId,
            'data'     => $data,
        ]);

        // Sync component state
        $this->syncCartState();
    }

    /** Remove item from cart with Livewire reactivity */
    public function removeFromCart(string $rowId): void
    {
        $this->cart->remove($rowId);

        // Trigger cart updated event
        $this->dispatch('cart-updated', [
            'instance' => $this->cartInstance,
            'action'   => 'remove',
            'rowId'    => $rowId,
        ]);

        // Sync component state
        $this->syncCartState();
    }

    /** Clear cart with Livewire reactivity */
    public function clearCart(): void
    {
        $this->cart->destroy();

        // Trigger cart updated event
        $this->dispatch('cart-updated', [
            'instance' => $this->cartInstance,
            'action'   => 'clear',
        ]);

        // Sync component state
        $this->syncCartState();
    }

    /** Listen for cart updates from other components */
    #[On('cart-updated')]
    public function onCartUpdated(array $data): void
    {
        // Only react to updates for the same cart instance
        if (isset($data['instance']) && $data['instance'] === $this->cartInstance) {
            $this->syncCartState();
        }
    }

    /** Get cart instance */
    public function getCartProperty(): CartService
    {
        if ($this->cart === null) {
            $this->initializeCart();
        }

        return $this->cart;
    }

    /** Get cart content for display */
    public function getCartContentProperty()
    {
        return $this->cart->content();
    }

    /** Get cart count */
    public function getCartCountProperty(): int
    {
        return $this->cart->count();
    }

    /** Get cart total */
    public function getCartTotalProperty(): float
    {
        return $this->cart->total();
    }

    /** Get cart subtotal */
    public function getCartSubtotalProperty(): float
    {
        return $this->cart->subtotal();
    }

    /** Get cart tax */
    public function getCartTaxProperty(): float
    {
        return $this->cart->tax();
    }

    /** Check if cart is empty */
    public function getCartEmptyProperty(): bool
    {
        return $this->cart->isEmpty();
    }

    /** Get cart storage statistics */
    public function getCartStatsProperty(): array
    {
        return $this->cart->getStorageStats();
    }
}
