<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Traits\LivewireCartTrait;
use App\Traits\WithAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductCart extends Component
{
    use LivewireCartTrait;
    use WithAlert;

    public float|int $global_discount = 0;

    public float|int $global_tax = 0;

    public bool $discountModal = false;

    public float|int $shipping_amount = 0;

    public array $quantity = [];

    public array $price = [];

    public array $check_quantity = [];

    public int|string|null $warehouse_id = null;

    public array $discount_type = [];

    public array $item_discount = [];

    public mixed $data = null;

    public float|int $total_with_shipping = 0;

    public function mount(string $cartInstance, int|string|null $warehouseId = null, mixed $data = null): void
    {
        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        if ($data) {
            $this->data = $data;
            $this->global_discount = $data->discount_percentage;
            $this->global_tax = $data->tax_percentage;
            $this->shipping_amount = $data->shipping_amount;
            $this->warehouse_id = $data->warehouse_id;

            $this->updatedGlobalTax();
            $this->updatedGlobalDiscount();
            $this->updatedTotalShipping();
        } else {
            $this->warehouse_id = $warehouseId ?? settings()->default_warehouse_id;
        }

        $cart_items = $this->getCart()->content();

        foreach ($cart_items as $cart_item) {
            $this->quantity[$cart_item->id] = $cart_item->quantity;
            $this->price[$cart_item->id] = $cart_item->price;
            $this->discount_type[$cart_item->id] = $cart_item->attributes->product_discount_type ?? 'fixed';
            $this->item_discount[$cart_item->id] = (($cart_item->attributes->product_discount_type ?? 'fixed') === 'fixed')
                ? ($cart_item->attributes->product_discount ?? 0)
                : ($cart_item->price > 0 ? round(100 * ($cart_item->attributes->product_discount ?? 0) / $cart_item->price) : 0);
        }
    }

    #[On('productSelected')]
    public function productSelected($productId, int $warehouseId): void
    {
        $this->warehouse_id = $warehouseId;

        $product = Product::findOrFail($productId);

        $exists = $this->getCart()->search(static fn ($cartItem): bool => $cartItem->id === $product->id);

        if ($exists->isNotEmpty()) {
            $this->alert('error', __('Product already added to cart!'));

            return;
        }

        $productWarehouse = ProductWarehouse::where('product_id', $productId)
            ->where('warehouse_id', $this->warehouse_id)
            ->first();

        $cartItem = $this->createCartItem($product, $productWarehouse);

        $this->addToCart($cartItem);
        $this->updateQuantityAndCheckQuantity($product->id, $productWarehouse ? $productWarehouse->qty : 0, $cartItem['price']);
    }

    private function createCartItem(Product $product, ?ProductWarehouse $productWarehouse): array
    {
        $calculation = $this->calculatePrices($product, $productWarehouse);

        return [
            'id' => $product->id,
            'name' => $product->name,
            'quantity' => 1,
            'price' => $productWarehouse ? $productWarehouse->price : ($product->price ?? 0.00),
            'attributes' => array_merge($calculation, [
                'product_discount' => 0.00,
                'product_discount_type' => 'fixed',
                'code' => $product->code,
                'image' => $product->image,
                'stock' => $productWarehouse ? $productWarehouse->qty : 0,
                'unit' => $product->unit,
                'weight' => 1,
            ]),
        ];
    }

    private function calculatePrices(Product $product, ?ProductWarehouse $productWarehouse): array
    {
        $price = $productWarehouse ? $productWarehouse->price : ($product->price ?? 0.00);
        $unit_price = $price;
        $product_tax = 0.00;
        $sub_total = $price;

        if ($product->tax_type === 1) {
            $tax = $price * $product->tax_amount / 100;
            $price += $tax;
            $product_tax = $tax;
            $sub_total = $price;
        } elseif ($product->tax_type === 2) {
            $tax = $price * $product->tax_amount / 100;
            $unit_price -= $tax;
            $product_tax = $tax;
        }

        return [
            'price' => $price,
            'unit_price' => $unit_price,
            'product_tax' => $product_tax,
            'sub_total' => $sub_total,
        ];
    }

    private function updateQuantityAndCheckQuantity($productId, int $quantity, float $price = 0): void
    {
        $this->check_quantity[$productId] = $quantity;
        $this->quantity[$productId] = 1;
        $this->price[$productId] = $price;
    }

    public function updatePrice(int|string $productId, ?string $rowId = null): void
    {
        if ($rowId === null) {
            $cartItem = $this->getCart()->search(fn ($item) => $item->id == $productId)->first();

            if (! $cartItem) {
                $this->alert('error', 'Product not found in cart!');

                return;
            }

            $rowId = $cartItem->rowId;
        }

        $this->updateCartItem($rowId, [
            'price' => $this->price[$productId],
        ]);

        $cart_item = $this->getCart()->get($rowId);

        $this->updateCartItem($rowId, [
            'attributes' => [
                'sub_total' => $cart_item['price'] * $cart_item['quantity'],
                'code' => $cart_item['attributes']['code'],
                'stock' => $cart_item['attributes']['stock'],
                'unit' => $cart_item['attributes']['unit'],
                'product_tax' => $cart_item['attributes']['product_tax'],
                'unit_price' => $cart_item['price'],
                'product_discount' => $cart_item['attributes']['product_discount'],
                'product_discount_type' => $cart_item['attributes']['product_discount_type'],
                'image' => $cart_item['attributes']['image'] ?? null,
                'weight' => $cart_item['attributes']['weight'] ?? 1,
            ],
        ]);
    }

    public function updatedGlobalTax(): void
    {
        $this->getCart()->setGlobalTax((float) $this->global_tax);
    }

    public function updatedGlobalDiscount(): void
    {
        $this->getCart()->setGlobalDiscount((float) $this->global_discount);
    }

    public function updatedTotalShipping(): void
    {
        // Handled by computed properties
    }

    public function updatedShippingAmount(float|int $value): void
    {
        $this->shipping_amount = $value;
    }

    public function discountModal(int|string $productId, string $rowId): void
    {
        $this->updateQuantity($rowId, $productId);
        $this->discountModal = true;
    }

    public function updateQuantity(string $rowId, int|string $productId): void
    {
        if ($rowId === null) {
            $cartItem = $this->getCart()->search(fn ($item) => $item->id == $productId)->first();

            if (! $cartItem) {
                $this->alert('error', 'Product not found in cart!');

                return;
            }

            $rowId = $cartItem->rowId;
        }

        if (($this->cartInstance === 'sale' || $this->cartInstance === 'purchase_return') && ($this->check_quantity[$productId] ?? 0) < ($this->quantity[$productId] ?? 0)) {
            $this->alert('error', 'Quantity is greater than in stock!');

            return;
        }

        $this->updateCartItem($rowId, ['quantity' => $this->quantity[$productId]]);

        $cart_item = $this->getCart()->get($rowId);

        $this->updateCartItem($rowId, [
            'attributes' => [
                'sub_total' => $cart_item['price'] * $cart_item['quantity'],
                'code' => $cart_item['attributes']['code'],
                'stock' => $cart_item['attributes']['stock'],
                'unit' => $cart_item['attributes']['unit'],
                'product_tax' => $cart_item['attributes']['product_tax'],
                'unit_price' => $cart_item['price'],
                'product_discount' => $cart_item['attributes']['product_discount'],
                'product_discount_type' => $cart_item['attributes']['product_discount_type'],
                'image' => $cart_item['attributes']['image'] ?? null,
                'weight' => $cart_item['attributes']['weight'] ?? 1,
            ],
        ]);
    }

    public function removeItem(string $rowId): void
    {
        $this->removeFromCart($rowId);
    }

    public function updatedDiscountType(mixed $value, mixed $name): void
    {
        $this->item_discount[$name] = 0;
    }

    public function productDiscount(string $rowId, int|string $productId): void
    {
        $cart_item = $this->getCart()->get($rowId);

        if ($this->discount_type[$productId] === 'fixed') {
            $this->updateCartItem($rowId, [
                'price' => $cart_item['price'] + $cart_item['attributes']['product_discount'] - $this->item_discount[$productId],
            ]);

            $discount_amount = $this->item_discount[$productId];

            $this->updateCartOptions($rowId, $productId, $cart_item, $discount_amount);
        } elseif ($this->discount_type[$productId] === 'percentage') {
            $discount_amount = ($cart_item['price'] + $cart_item['attributes']['product_discount']) * $this->item_discount[$productId] / 100;

            $this->updateCartItem($rowId, [
                'price' => $cart_item['price'] + $cart_item['attributes']['product_discount'] - $discount_amount,
            ]);

            $this->updateCartOptions($rowId, $productId, $cart_item, $discount_amount);
        }

        $this->alert('success', __('Product discount set successfully!'));
        $this->discountModal = false;
    }

    private function updateCartOptions(string $rowId, int|string $productId, mixed $cartItem, float $discountAmount): void
    {
        $this->updateCartItem($rowId, [
            'attributes' => [
                'sub_total' => $cartItem['price'] * $cartItem['quantity'],
                'code' => $cartItem['attributes']['code'],
                'stock' => $cartItem['attributes']['stock'],
                'unit' => $cartItem['attributes']['unit'],
                'product_tax' => $cartItem['attributes']['product_tax'],
                'unit_price' => $cartItem['price'],
                'product_discount' => $discountAmount,
                'product_discount_type' => $cartItem['attributes']['product_discount_type'],
                'image' => $cartItem['attributes']['image'] ?? null,
                'weight' => $cartItem['attributes']['weight'] ?? 1,
            ],
        ]);
    }

    #[On('warehouseSelected')]
    public function updatedWarehouseId(int $warehouseId): void
    {
        $this->warehouse_id = $warehouseId;
    }

    protected function syncCartState(): void
    {
        $cart_items = $this->getCart()->content();

        $currentIds = [];
        foreach ($cart_items as $cart_item) {
            $currentIds[] = $cart_item->id;
        }

        foreach (array_keys($this->quantity) as $productId) {
            if (! in_array($productId, $currentIds)) {
                unset($this->quantity[$productId]);
                unset($this->price[$productId]);
                unset($this->check_quantity[$productId]);
                unset($this->discount_type[$productId]);
                unset($this->item_discount[$productId]);
            }
        }
    }

    public function render(): \Illuminate\View\View
    {
        $cart_items = $this->getCart()->content();

        foreach ($cart_items as $cart_item) {
            $attributes = $cart_item->attributes;
            $discount_type = $attributes['product_discount_type'] ?? 'fixed';

            $this->discount_type[$cart_item->id] = $discount_type;

            if ($discount_type === 'fixed') {
                $this->item_discount[$cart_item->id] = $attributes['product_discount'] ?? 0;
            } else {
                $price = $cart_item->price ?? 0;
                $discount = $attributes['product_discount'] ?? 0;
                $this->item_discount[$cart_item->id] = $price > 0 ? round(100 * $discount / $price) : 0;
            }
        }

        return view('livewire.utils.product-cart', [
            'cart_items' => $cart_items,
        ]);
    }
}
