<?php

declare(strict_types=1);

namespace App\Livewire\Sales;

use App\Enums\MovementType;
use App\Models\Movement;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetails;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Livewire\Utils\WithModels;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class Edit extends Component
{
    use LivewireAlert;
    use WithModels;

    public $sale;

    public $products;

    public $product;

    public $quantity;

    public $reference;

    public $check_quantity;

    public $price;

    #[Validate('integer|min:0|max:100')]
    public $tax_percentage;

    #[Validate('integer|min:0|max:100')]
    public $discount_percentage;

    #[Validate('required|integer')]
    public $customer_id;

    #[Validate('required|integer')]
    public $warehouse_id;

    #[Validate('required|numeric')]
    public $total_amount;

    #[Validate('numeric')]
    public $paid_amount;

    #[Validate('numeric')]
    public $shipping_amount;

    public $note;

    #[Validate('required|integer|max:255')]
    public $status;

    #[Validate('required|string|max:255')]
    public $payment_method;

    public $date;

    public $discount_type;

    public $item_discount;

    public $sale_details;

    public function mount($id): void
    {
        $this->sale = Sale::findOrFail($id);

        abort_if(Gate::denies('sale update'), 403);

        $this->sale_details = $this->sale->saleDetails;

        Cart::instance('sale')->destroy();

        $cart = Cart::instance('sale');

        foreach ($this->sale_details as $sale_detail) {
            $cart->add([
                'id'      => $sale_detail->product_id,
                'name'    => $sale_detail->name,
                'qty'     => $sale_detail->quantity,
                'price'   => $sale_detail->price,
                'weight'  => 1,
                'options' => [
                    'product_discount'      => $sale_detail->product_discount_amount,
                    'product_discount_type' => $sale_detail->product_discount_type,
                    'sub_total'             => $sale_detail->sub_total,
                    'code'                  => $sale_detail->code,
                    'stock'                 => Product::findOrFail($sale_detail->product_id)->quantity,
                    'product_tax'           => $sale_detail->product_tax_amount,
                    'unit_price'            => $sale_detail->unit_price,
                ],
            ]);
        }

        $this->reference = $this->sale->reference;
        $this->date = $this->sale->date;
        $this->customer_id = $this->sale->customer_id;
        $this->warehouse_id = $this->sale->warehouse_id;
        $this->status = $this->sale->status;
        $this->payment_method = $this->sale->payment_method;
        $this->paid_amount = $this->sale->paid_amount;
        $this->note = $this->sale->note;
        $this->tax_percentage = $this->sale->tax_percentage;
        $this->discount_percentage = $this->sale->discount_percentage;
        $this->shipping_amount = $this->sale->shipping_amount;
        $this->total_amount = $this->sale->total_amount;
    }

    public function proceed(): void
    {
        if ($this->customer_id !== null) {
            $this->store();
        } else {
            $this->alert('error', __('Please select a customer!'));
        }
    }

    public function update(): void
    {
        if ( ! $this->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        DB::transaction(function () {
            $this->validate();

            if (in_array($this->sale->status, [SaleStatus::COMPLETED, SaleStatus::RETURNED, SaleStatus::CANCELED])) {
                $this->alert('error', __('Cannot update a completed, returned or canceled sale.'));

                return redirect()->back();
            }

            // Determine payment status
            $due_amount = $this->total_amount - $this->paid_amount;

            if ($due_amount === $this->total_amount) {
                $payment_status = PaymentStatus::PENDING;
                $this->status = SaleStatus::PENDING;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::PARTIAL;
                $this->status = SaleStatus::PENDING;
            } else {
                $payment_status = PaymentStatus::PAID;
                $this->status = SaleStatus::COMPLETED;
            }

            // Delete previous sale details
            foreach ($this->sale->saleDetails as $sale_detail) {
                $sale_detail->delete();
            }

            $this->sale->update([
                'date'                => $this->date,
                'reference'           => $this->reference,
                'customer_id'         => $this->customer_id,
                'tax_percentage'      => $this->tax_percentage,
                'discount_percentage' => $this->discount_percentage,
                'shipping_amount'     => $this->shipping_amount * 100,
                'paid_amount'         => $this->paid_amount * 100,
                'total_amount'        => $this->total_amount * 100,
                'due_amount'          => $due_amount * 100,
                'status'              => $this->status,
                'payment_status'      => $payment_status,
                'payment_method'      => $this->payment_method,
                'note'                => $this->note,
                'tax_amount'          => (int) (Cart::instance('sale')->tax() * 100),
                'discount_amount'     => (int) (Cart::instance('sale')->discount() * 100),
            ]);

            foreach (Cart::instance('sale')->content() as $cart_item) {
                SaleDetails::create([
                    'sale_id'                 => $this->sale->id,
                    'product_id'              => $cart_item->id,
                    'warehouse_id'            => $this->warehouse_id,
                    'name'                    => $cart_item->name,
                    'code'                    => $cart_item->options->code,
                    'quantity'                => $cart_item->qty,
                    'price'                   => $cart_item->price * 100,
                    'unit_price'              => $cart_item->options->unit_price * 100,
                    'sub_total'               => $cart_item->options->sub_total * 100,
                    'product_discount_amount' => $cart_item->options->product_discount * 100,
                    'product_discount_type'   => $cart_item->options->product_discount_type,
                    'product_tax_amount'      => $cart_item->options->product_tax * 100,
                ]);

                $product = Product::findOrFail($cart_item->id);
                $product_warehouse = ProductWarehouse::where('product_id', $product->id)
                    ->where('warehouse_id', $this->warehouse_id)
                    ->first();

                $new_quantity = $product_warehouse->qty + $cart_item->qty;

                $product_warehouse->update([
                    'qty' => $new_quantity,
                ]);

                $movement = new Movement([
                    'type'         => MovementType::PURCHASE,
                    'quantity'     => $cart_item->qty,
                    'price'        => $cart_item->price * 100,
                    'date'         => date('Y-m-d'),
                    'movable_type' => $product::class,
                    'movable_id'   => $product->id,
                    'user_id'      => Auth::user()->id,
                ]);

                $movement->save();
            }

            Cart::instance('sale')->destroy();

            $this->alert('success', __('Sale Updated succesfully !'));

            return redirect()->route('sales.index');
        });
    }

    public function render()
    {
        return view('livewire.sales.edit');
    }

    public function updatedWarehouseId($value): void
    {
        $this->warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->warehouse_id);
    }

    public function updatedStatus($value): void
    {
        if ($value === SaleStatus::COMPLETED->value) {
            $this->paid_amount = $this->total_amount;
        }
    }
}
