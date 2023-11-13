<?php

declare(strict_types=1);

namespace App\Http\Livewire\Purchase;

use App\Enums\MovementType;
use App\Models\Movement;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class Edit extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = [
        'refreshIndex' => '$refresh',
    ];

    public $cart_instance;

    public $suppliers;

    public $purchase;

    public $products;

    public $supplier_id;

    public $warehouse_id;

    public $product;

    public $quantity;

    public $reference;

    public $total_amount;

    public $check_quantity;

    public $price;

    public $tax_percentage;

    public $discount_percentage;

    public $shipping_amount;

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
            'warehouse_id'        => 'required|integer',
            'supplier_id'         => 'required|integer',
            'reference'           => 'required|string|max:255',
            'tax_percentage'      => 'required|integer|min:0|max:100',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'shipping_amount'     => 'required|numeric',
            'total_amount'        => 'required|numeric',
            'paid_amount'         => 'required|numeric',
            'status'              => 'required',
            'payment_method'      => 'required|string|max:255',
            'note'                => 'nullable|string|max:1000',
            'date'                => 'required|string|max:1000',
        ];
    }

    public function mount(Purchase $purchase)
    {
        $this->purchase = Purchase::findOrFail($purchase->id);
        $this->reference = $this->purchase->reference;
        $this->date = $this->purchase->date;
        $this->supplier_id = $this->purchase->supplier_id;
        $this->warehouse_id = $this->purchase->warehouse_id;
        $this->status = $this->purchase->status;
        $this->payment_method = $this->purchase->payment_method;
        $this->paid_amount = $this->purchase->paid_amount;
        $this->note = $this->purchase->note;
        $this->tax_percentage = $this->purchase->tax_percentage;
        $this->discount_percentage = $this->purchase->discount_percentage;
        $this->shipping_amount = $this->purchase->shipping_amount;
        $this->total_amount = $this->purchase->total_amount;
    }

    public function update()
    {
        if ( ! $this->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        DB::transaction(function () {
            $this->validate();

            if (in_array($this->purchase->status, [PurchaseStatus::COMPLETED, PurchaseStatus::RETURNED, PurchaseStatus::CANCELED])) {
                $this->alert('error', __('Cannot update a completed, returned or canceled purchase.'));

                return redirect()->back();
            }

            // Determine payment status
            $due_amount = $this->total_amount - $this->paid_amount;

            if ($due_amount === $this->total_amount) {
                $payment_status = PaymentStatus::PENDING;
                $this->status = PurchaseStatus::PENDING;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::PARTIAL;
                $this->status = PurchaseStatus::PENDING;
            } else {
                $payment_status = PaymentStatus::PAID;
                $this->status = PurchaseStatus::COMPLETED;
            }

            // Delete previous purchase details
            foreach ($this->purchase->purchaseDetails as $purchase_detail) {
                $purchase_detail->delete();
            }

            $this->purchase->update([
                'date'                => $this->date,
                'reference'           => $this->reference,
                'supplier_id'         => $this->supplier_id,
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
                'tax_amount'          => Cart::instance('purchase')->tax() * 100,
                'discount_amount'     => Cart::instance('purchase')->discount() * 100,
            ]);

            foreach (Cart::instance('purchase')->content() as $cart_item) {
                PurchaseDetail::create([
                    'purchase_id'             => $this->purchase->id,
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

                if ( ! $product_warehouse) {
                    $product_warehouse = new ProductWarehouse([
                        'product_id'   => $cart_item->id,
                        'warehouse_id' => $this->warehouse_id,
                        'price'        => $cart_item->price * 100,
                        'cost'         => $cart_item->options->unit_price * 100,
                        'qty'          => 0,
                    ]);
                }

                $new_quantity = $product_warehouse->qty + $cart_item->qty;
                $new_cost = (($product_warehouse->cost * $product_warehouse->qty) + ($cart_item->options->unit_price * $cart_item->qty)) / $new_quantity;

                $product_warehouse->update([
                    'qty'  => $new_quantity,
                    'cost' => $new_cost,
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

            Cart::instance('purchase')->destroy();

            $this->alert('success', __('Purchase Updated succesfully !'));

            return redirect()->route('purchases.index');
        });
    }

    public function render()
    {
        return view('livewire.purchase.edit');
    }

    public function hydrate(): void
    {
        $this->total_amount = $this->calculateTotal();
    }

    public function calculateTotal(): mixed
    {
        return Cart::instance($this->cart_instance)->total() + $this->shipping_amount;
    }

    public function resetCart(): void
    {
        Cart::instance($this->cart_instance)->destroy();
    }

    public function getSupplierProperty()
    {
        return Supplier::pluck('name', 'id')->toArray();
    }

    public function getWarehousesProperty()
    {
        return Warehouse::pluck('name', 'id')->toArray();
    }

    public function updatedWarehouseId($warehouse_id)
    {
        $this->warehouse_id = $warehouse_id;
        $this->emit('warehouseSelected', $warehouse_id);
    }

    public function updatedStatus($value)
    {
        if ($value === PurchaseStatus::COMPLETED->value) {
            $this->paid_amount = $this->total_amount;
        } else {
            $this->paid_amount = 0;
        }
    }
}
