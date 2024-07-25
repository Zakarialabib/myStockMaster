<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\ProductWarehouse;
use Livewire\Component;

class ProductCart extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = [
        'productSelected', 'discountModalRefresh',
        'warehouseSelected' => 'updatedWarehouseId',
    ];

    public $cart_instance;

    public $global_discount = 0;

    public $global_tax = 0;

    public $discountModal = false;

    public $shipping_amount;

    public $quantity = [];

    public $price;

    public $check_quantity = [];

    public $warehouse_id;

    public $discount_type;

    public $item_discount;

    public $data;

    public $total_with_shipping;

    public function mount($cartInstance, $data = null)
    {
        $this->cart_instance = $cartInstance;

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

            $cart_items = Cart::instance($this->cart_instance)->content();

            foreach ($cart_items as $index => $cart_item) {
                $this->check_quantity[$cart_item->id] = [$cart_item->options->stock];
                $this->quantity[$cart_item->id] = $cart_item->qty;
                $this->discount_type[$cart_item->id] = $cart_item->options->product_discount_type;
                $this->item_discount[$cart_item->id] = ($cart_item->options->product_discount_type === 'fixed')
                    ? $cart_item->options->product_discount
                    : round(100 * $cart_item->options->product_discount / $cart_item->price);
            }
        } else {
            $this->updatedGlobalTax();
            $this->updatedGlobalDiscount();
            $this->updatedTotalShipping();
        }
    }

    public function productSelected($product): void
    {
        if (empty($product)) {
            $this->alert('error', __('Something went wrong!'));

            return;
        }

        $cart = Cart::instance($this->cart_instance);
        $exists = $cart->search(fn ($cartItem) => $cartItem->id === $product['id']);

        if ($exists->isNotEmpty()) {
            $this->alert('error', __('Product already added to cart!'));

            return;
        }

        $productWarehouse = ProductWarehouse::where('product_id', $product['id'])
            ->where('warehouse_id', $this->warehouse_id)
            ->first();

        $cartItem = $this->createCartItem($product, $productWarehouse);

        $cart->add($cartItem);
        $this->updateQuantityAndCheckQuantity($product['id'], $productWarehouse->qty);
    }

    public function calculate($product): array
    {
        $productWarehouse = ProductWarehouse::where('product_id', $product['id'])
            ->where('warehouse_id', $this->warehouse_id)
            ->first();

        return $this->calculatePrices($product, $productWarehouse);
    }

    private function calculatePrices($product, $productWarehouse)
    {
        $price = $productWarehouse->price;
        $unit_price = $price;
        $product_tax = 0.00;
        $sub_total = $price;

        if ($product['tax_type'] === 1) {
            $tax = $price * $product['order_tax'] / 100;
            $price += $tax;
            $product_tax = $tax;
            $sub_total = $price;
        } elseif ($product['tax_type'] === 2) {
            $tax = $price * $product['order_tax'] / 100;
            $unit_price -= $tax;
            $product_tax = $tax;
        }

        return ['price' => $price, 'unit_price' => $unit_price, 'product_tax' => $product_tax, 'sub_total' => $sub_total];
    }

    private function updateQuantityAndCheckQuantity($productId, $quantity)
    {
        $this->check_quantity[$productId] = $quantity;
        $this->quantity[$productId] = 1;
    }

    private function createCartItem($product, $productWarehouse)
    {
        $calculation = $this->calculate($product);

        return [
            'id'      => $product['id'],
            'name'    => $product['name'],
            'qty'     => 1,
            'price'   => $productWarehouse->price,
            'weight'  => 1,
            'options' => array_merge($calculation, [
                'product_discount'      => 0.00,
                'product_discount_type' => 'fixed',
                'code'                  => $product['code'],
                'stock'                 => $productWarehouse->qty,
                'unit'                  => $product['unit'],
            ]),
        ];
    }

    public function updatePrice($row_id, $product_id)
    {
        Cart::instance($this->cart_instance)->update($row_id, [
            'price' => $this->price[$product_id],
        ]);

        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        Cart::instance($this->cart_instance)->update($row_id, [
            'options' => [
                'sub_total'             => $cart_item->price * $cart_item->qty,
                'code'                  => $cart_item->options->code,
                'stock'                 => $cart_item->options->stock,
                'unit'                  => $cart_item->options->unit,
                'product_tax'           => $cart_item->options->product_tax,
                'unit_price'            => $cart_item->price,
                'product_discount'      => $cart_item->options->product_discount,
                'product_discount_type' => $cart_item->options->product_discount_type,
            ],
        ]);
    }

    public function updatedGlobalTax()
    {
        Cart::instance($this->cart_instance)->setGlobalTax((int) $this->global_tax);
    }

    public function updatedGlobalDiscount()
    {
        Cart::instance($this->cart_instance)->setGlobalDiscount((int) $this->global_discount);
    }

    public function updatedTotalShipping()
    {
        Cart::instance($this->cart_instance)->total() + (float) $this->shipping_amount;
    }

    public function updatedShippingAmount($value)
    {
        $this->shipping_amount = $value;
    }

    public function discountModal($product_id, $row_id): void
    {
        $this->updateQuantity($row_id, $product_id);

        $this->discountModal = true;
    }

    public function updateQuantity($row_id, $product_id)
    {
        if ($this->cart_instance === 'sale' || $this->cart_instance === 'purchase_return') {
            if ($this->check_quantity[$product_id] < $this->quantity[$product_id]) {
                $this->alert('error', 'Quantity is greater than in stock!');

                return;
            }
        }

        Cart::instance($this->cart_instance)->update($row_id, $this->quantity[$product_id]);

        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        Cart::instance($this->cart_instance)->update($row_id, [
            'options' => [
                'sub_total'             => $cart_item->price * $cart_item->qty,
                'code'                  => $cart_item->options->code,
                'stock'                 => $cart_item->options->stock,
                'unit'                  => $cart_item->options->unit,
                'product_tax'           => $cart_item->options->product_tax,
                'unit_price'            => $cart_item->options->unit_price,
                'product_discount'      => $cart_item->options->product_discount,
                'product_discount_type' => $cart_item->options->product_discount_type,
            ],
        ]);
    }

    public function removeItem($row_id)
    {
        Cart::instance($this->cart_instance)->remove($row_id);
    }

    public function updatedDiscountType($value, $name)
    {
        $this->item_discount[$name] = 0;
    }

    public function productDiscount($row_id, $product_id): void
    {
        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        if ($this->discount_type[$product_id] === 'fixed') {
            Cart::instance($this->cart_instance)
                ->update($row_id, [
                    'price' => $cart_item->price + $cart_item->options->product_discount - $this->item_discount[$product_id],
                ]);

            $discount_amount = $this->item_discount[$product_id];

            $this->updateCartOptions($row_id, $product_id, $cart_item, $discount_amount);
        } elseif ($this->discount_type[$product_id] === 'percentage') {
            $discount_amount = ($cart_item->price + $cart_item->options->product_discount) * $this->item_discount[$product_id] / 100;

            Cart::instance($this->cart_instance)
                ->update($row_id, [
                    'price' => $cart_item->price + $cart_item->options->product_discount - $discount_amount,
                ]);

            $this->updateCartOptions($row_id, $product_id, $cart_item, $discount_amount);
        }
        $this->alert('success', __('Product discount set successfully!'));

        $this->discountModal = false;
    }

    public function updateCartOptions($row_id, $product_id, $cart_item, $discount_amount)
    {
        Cart::instance($this->cart_instance)->update($row_id, [
            'options' => [
                'sub_total'             => $cart_item->price * $cart_item->qty,
                'code'                  => $cart_item->options->code,
                'stock'                 => $cart_item->options->stock,
                'unit'                  => $cart_item->options->unit,
                'product_tax'           => $cart_item->options->product_tax,
                'unit_price'            => $cart_item->options->unit_price,
                'product_discount'      => $discount_amount,
                'product_discount_type' => $cart_item->options->product_discount_type,
            ],
        ]);
    }

    public function updatedWarehouseId($value)
    {
        $this->warehouse_id = $value;
    }

    public function render()
    {
        $cart_items = Cart::instance($this->cart_instance)->content();

        foreach ($cart_items as $index => $cart_item) {
            $this->discount_type[$cart_item->id] = $cart_item->options->product_discount_type;
            $this->item_discount[$cart_item->id] = ($cart_item->options->product_discount_type === 'fixed')
                ? $cart_item->options->product_discount
                : round(100 * $cart_item->options->product_discount / $cart_item->price);
        }

        return view('livewire.product-cart', [
            'cart_items' => $cart_items,
        ]);
    }
}
