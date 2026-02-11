<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use App\Models\Product;
use App\Models\ProductWarehouse;
use Livewire\Component;
use App\Traits\WithAlert;
use App\Traits\LivewireCartTrait;

class ProductCart extends Component
{
    use WithAlert;
    use LivewireCartTrait;

    public $listeners = [
        'productSelected',
        'warehouseSelected' => 'updatedWarehouseId',
    ];

    public $global_discount = 0;

    public $global_tax = 0;

    public $discountModal = false;

    public $shipping_amount;

    public $quantity = [];

    public $price = [];

    public $check_quantity = [];

    public $warehouse_id;

    public $discount_type;

    public $item_discount;

    public $data;

    public $total_with_shipping;

    public function mount($cartInstance, $data = null): void
    {
        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        $this->shipping_amount = 0.00;

        $this->discount_type = [];
        $this->item_discount = [];

        if ($data) {
            $this->data = $data;

            $this->global_discount = $data->discount_percentage;
            $this->global_tax = $data->tax_percentage;
            $this->shipping_amount = $data->shipping_amount;
            $this->warehouse_id = $data->warehouse_id;

            $this->updatedGlobalTax();
            $this->updatedGlobalDiscount();
            $this->updatedTotalShipping();

            $cart_items = $this->cart->content();

            foreach ($cart_items as $cart_item) {
                // $this->check_quantity[$cart_item['id']] = [$cart_item['attributes']['stock']]; // Cannot use object of type App\Services\CartItem as array
                $this->quantity[$cart_item['id']] = $cart_item['quantity'];
                $this->discount_type[$cart_item['id']] = $cart_item['attributes']['product_discount_type'];
                $this->item_discount[$cart_item['id']] = ($cart_item['attributes']['product_discount_type'] === 'fixed')
                    ? $cart_item['attributes']['product_discount']
                    : round(100 * $cart_item['attributes']['product_discount'] / $cart_item['price']);
            }
        } else {
            $this->updatedGlobalTax();
            $this->updatedGlobalDiscount();
            $this->updatedTotalShipping();
            $this->warehouse_id = settings()->default_warehouse_id;
        }
    }

    public function productSelected($id): void
    {
        $product = Product::find($id);

        $exists = $this->cart->search(static fn ($cartItem): bool => $cartItem->id === $product->id);

        if ($exists->isNotEmpty()) {
            $this->alert('error', __('Product already added to cart!'));

            return;
        }

        $productWarehouse = ProductWarehouse::where('product_id', $id)
            ->where('warehouse_id', $this->warehouse_id)
            ->first();

        $cartItem = $this->createCartItem($product, $productWarehouse);

        $this->addToCart($cartItem);
        $this->updateQuantityAndCheckQuantity($product->id, $productWarehouse ? $productWarehouse->qty : 0);
    }

    public function calculate($product): array
    {
        $productWarehouse = ProductWarehouse::where('product_id', $product->id)
            ->where('warehouse_id', $this->warehouse_id)
            ->first();

        return $this->calculatePrices($product, $productWarehouse);
    }

    private function calculatePrices($product, $productWarehouse)
    {
        // Use product price if warehouse price is not available
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

        return ['price' => $price, 'unit_price' => $unit_price, 'product_tax' => $product_tax, 'sub_total' => $sub_total];
    }

    private function updateQuantityAndCheckQuantity($productId, $quantity): void
    {
        $this->check_quantity[$productId] = $quantity;
        $this->quantity[$productId] = 1;
    }

    private function createCartItem($product, $productWarehouse): array
    {
        $calculation = $this->calculate($product);

        return [
            'id'         => $product->id,
            'name'       => $product->name,
            'quantity'   => 1,
            'price'      => $productWarehouse ? $productWarehouse->price : ($product->price ?? 0.00),
            'attributes' => array_merge($calculation, [
                'product_discount'      => 0.00,
                'product_discount_type' => 'fixed',
                'code'                  => $product->code,
                'stock'                 => $productWarehouse ? $productWarehouse->qty : 0,
                'unit'                  => $product->unit,
                'weight'                => 1,
            ]),
        ];
    }

    public function updatePrice($product_id, $row_id = null): void
    {
        // If row_id is not provided, find it by product_id
        if ($row_id === null) {
            $cartItem = $this->cart->search(function ($cartItem) use ($product_id) {
                return $cartItem->id == $product_id;
            })->first();

            if ( ! $cartItem) {
                $this->alert('error', 'Product not found in cart!');

                return;
            }

            $row_id = $cartItem->rowId;
        }

        $this->cart->update($row_id, [
            'price' => $this->price[$product_id],
        ]);

        $cart_item = $this->cart->get($row_id);

        $this->cart->update($row_id, [
            'attributes' => [
                'sub_total'             => $cart_item['price'] * $cart_item['quantity'],
                'code'                  => $cart_item['attributes']['code'],
                'stock'                 => $cart_item['attributes']['stock'],
                'unit'                  => $cart_item['attributes']['unit'],
                'product_tax'           => $cart_item['attributes']['product_tax'],
                'unit_price'            => $cart_item['price'],
                'product_discount'      => $cart_item['attributes']['product_discount'],
                'product_discount_type' => $cart_item['attributes']['product_discount_type'],
            ],
        ]);
    }

    public function updatedGlobalTax(): void
    {
        $this->cart->setGlobalTax((int) $this->global_tax);
    }

    public function updatedGlobalDiscount(): void
    {
        $this->cart->setGlobalDiscount((int) $this->global_discount);
    }

    public function updatedTotalShipping(): void
    {
        $this->cart->total();
    }

    public function updatedShippingAmount($value): void
    {
        $this->shipping_amount = $value;
    }

    public function discountModal($product_id, $row_id): void
    {
        $this->updateQuantity($row_id, $product_id);

        $this->discountModal = true;
    }

    public function updateQuantity($product_id, $row_id = null): void
    {
        // If row_id is not provided, find it by product_id
        if ($row_id === null) {
            $cartItem = $this->cart->search(function ($cartItem) use ($product_id) {
                return $cartItem->id == $product_id;
            })->first();

            if ( ! $cartItem) {
                $this->alert('error', 'Product not found in cart!');

                return;
            }

            $row_id = $cartItem->rowId;
        }

        if (($this->cartInstance === 'sale' || $this->cartInstance === 'purchase_return') && $this->check_quantity[$product_id] < $this->quantity[$product_id]) {
            $this->alert('error', 'Quantity is greater than in stock!');

            return;
        }

        $this->cart->update($row_id, ['quantity' => $this->quantity[$product_id]]);

        $cart_item = $this->cart->get($row_id);

        $this->cart->update($row_id, [
            'attributes' => [
                'sub_total'             => $cart_item['price'] * $cart_item['quantity'],
                'code'                  => $cart_item['attributes']['code'],
                'stock'                 => $cart_item['attributes']['stock'],
                'unit'                  => $cart_item['attributes']['unit'],
                'product_tax'           => $cart_item['attributes']['product_tax'],
                'unit_price'            => $cart_item['attributes']['unit_price'],
                'product_discount'      => $cart_item['attributes']['product_discount'],
                'product_discount_type' => $cart_item['attributes']['product_discount_type'],
            ],
        ]);
    }

    public function removeItem($row_id): void
    {
        $this->removeFromCart($row_id);
    }

    public function updatedDiscountType($value, $name): void
    {
        $this->item_discount[$name] = 0;
    }

    public function productDiscount($row_id, $product_id): void
    {
        $cart_item = $this->cart->get($row_id);

        if ($this->discount_type[$product_id] === 'fixed') {
            $this->cart->update($row_id, [
                'price' => $cart_item['price'] + $cart_item['attributes']['product_discount'] - $this->item_discount[$product_id],
            ]);

            $discount_amount = $this->item_discount[$product_id];

            $this->updateCartOptions($row_id, $product_id, $cart_item, $discount_amount);
        } elseif ($this->discount_type[$product_id] === 'percentage') {
            $discount_amount = ($cart_item['price'] + $cart_item['attributes']['product_discount']) * $this->item_discount[$product_id] / 100;

            $this->cart->update($row_id, [
                'price' => $cart_item['price'] + $cart_item['attributes']['product_discount'] - $discount_amount,
            ]);

            $this->updateCartOptions($row_id, $product_id, $cart_item, $discount_amount);
        }

        $this->alert('success', __('Product discount set successfully!'));

        $this->discountModal = false;
    }

    public function updateCartOptions($row_id, $product_id, $cart_item, $discount_amount): void
    {
        $this->cart->update($row_id, [
            'attributes' => [
                'sub_total'             => $cart_item['price'] * $cart_item['quantity'],
                'code'                  => $cart_item['attributes']['code'],
                'stock'                 => $cart_item['attributes']['stock'],
                'unit'                  => $cart_item['attributes']['unit'],
                'product_tax'           => $cart_item['attributes']['product_tax'],
                'unit_price'            => $cart_item['attributes']['unit_price'],
                'product_discount'      => $discount_amount,
                'product_discount_type' => $cart_item['attributes']['product_discount_type'],
            ],
        ]);
    }

    public function updatedWarehouseId($value): void
    {
        $this->warehouse_id = $value;
    }

    public function render()
    {
        $cart_items = $this->cart->content();

        foreach ($cart_items as $cart_item) {
            // Ensure cart_item is an array and has required keys
            if ( ! is_array($cart_item) || ! isset($cart_item['id'])) {
                continue;
            }

            $attributes = $cart_item['attributes'] ?? [];
            $discount_type = is_array($attributes) ? ($attributes['product_discount_type'] ?? 'fixed') : 'fixed';

            $this->discount_type[$cart_item['id']] = $discount_type;

            if ($discount_type === 'fixed') {
                $this->item_discount[$cart_item['id']] = is_array($attributes) ? ($attributes['product_discount'] ?? 0) : 0;
            } else {
                $price = $cart_item['price'] ?? 0;
                $discount = is_array($attributes) ? ($attributes['product_discount'] ?? 0) : 0;
                $this->item_discount[$cart_item['id']] = $price > 0 ? round(100 * $discount / $price) : 0;
            }
        }

        return view('livewire.utils.product-cart', [
            'cart_items' => $cart_items,
        ]);
    }
}
