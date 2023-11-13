<?php

declare(strict_types=1);

namespace App\Http\Livewire\Sales;

use App\Enums\MovementType;
use App\Models\Movement;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\Customer;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class Edit extends Component
{
    use LivewireAlert;

    public $sale;
    public $products;
    public $customer_id;
    public $product;
    public $quantity;
    public $reference;
    public $total_amount;
    public $check_quantity;
    public $price;
    public $tax_percentage;
    public $discount_percentage;
    public $shipping_amount;
    public $warehouse_id;
    public $shipping;
    public $paid_amount;
    public $note;
    public $status;
    public $payment_method;
    public $date;
    public $discount_type;
    public $item_discount;
    public $listsForFields = [];

    public function rules(): array
    {
        return [
            'customer_id'         => 'required|numeric',
            'warehouse_id'        => 'required',
            'reference'           => 'required|string|max:255',
            'tax_percentage'      => 'required|integer|min:0|max:100',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'shipping_amount'     => 'required|numeric',
            'total_amount'        => 'required|numeric',
            'paid_amount'         => 'required|numeric',
            'status'              => 'required|integer|max:255',
            'payment_method'      => 'required|string|max:255',
            'note'                => 'nullable|string|max:1000',
        ];
    }

    public function mount($id)
    {
        $this->sale = Sale::findOrFail($id);
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

    public function proceed()
    {
        if ($this->customer_id !== null) {
            $this->update();
        } else {
            $this->alert('error', __('Please select a customer!'));
        }
    }

    public function update()
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
                'tax_amount'          => Cart::instance('sale')->tax() * 100,
                'discount_amount'     => Cart::instance('sale')->discount() * 100,
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
                    'movable_type' => get_class($product),
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

    public function getCustomersProperty()
    {
        return Customer::pluck('name', 'id')->toArray();
    }

    public function updatedWarehouseId($value)
    {
        $this->warehouse_id = $value;
        $this->emit('warehouseSelected', $this->warehouse_id);
    }

    public function updatedStatus($value)
    {
        if ($value === SaleStatus::COMPLETED->value) {
            $this->paid_amount = $this->total_amount;
        }
    }

    public function getWarehousesProperty()
    {
        return Warehouse::pluck('name', 'id')->toArray();
    }
}
