<?php

declare(strict_types=1);

namespace App\Livewire\Purchase;

use App\Enums\MovementType;
use App\Livewire\Utils\WithModels;
use App\Models\Movement;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;
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

    public $cart_instance;

    public $purchase_details;

    #[Validate('required')]
    public $warehouse_id;

    #[Validate('required')]
    public $supplier_id;

    #[Validate('required|integer|min:0|max:100')]
    public $tax_percentage;

    #[Validate('required|integer|min:0|max:100')]
    public $discount_percentage;

    #[Validate('required|numeric')]
    public $shipping_amount;

    #[Validate('required|numeric')]
    public $total_amount;

    #[Validate('required|numeric')]
    public $paid_amount;

    #[Validate('required|string|max:50')]
    public $status;

    #[Validate('required|string|max:50')]
    public $payment_method;

    #[Validate('nullable|string|max:1000')]
    public $note;

    public $purchase;

    public $products;

    public $product;

    public $quantity;

    public $reference;

    public $check_quantity;

    public $price;

    public $date;

    public $discount_type;

    public $item_discount;

    public $listsForFields = [];

    public function mount($id): void
    {
        $this->purchase = Purchase::findOrFail($id);

        $this->purchase_details = $this->purchase->purchaseDetails;

        Cart::instance('purchase')->destroy();

        $cart = Cart::instance('purchase');

        foreach ($this->purchase_details as $purchase_detail) {
            $cart->add([
                'id'      => $purchase_detail->product_id,
                'name'    => $purchase_detail->name,
                'qty'     => $purchase_detail->quantity,
                'price'   => $purchase_detail->price,
                'weight'  => 1,
                'options' => [
                    'product_discount'      => $purchase_detail->product_discount_amount,
                    'product_discount_type' => $purchase_detail->product_discount_type,
                    'sub_total'             => $purchase_detail->sub_total,
                    'code'                  => $purchase_detail->code,
                    'stock'                 => Product::findOrFail($purchase_detail->product_id)->quantity,
                    'product_tax'           => $purchase_detail->product_tax_amount,
                    'unit_price'            => $purchase_detail->unit_price,
                ],
            ]);
        }

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
        $this->warehouse_id = $this->purchase->warehouse_id;
    }

    public function update(): void
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
                    'movable_type' => $product::class,
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
        abort_if(Gate::denies('purchase update'), 403);

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

    public function updatedWarehouseId($warehouse_id): void
    {
        $this->warehouse_id = $warehouse_id;
        $this->dispatch('warehouseSelected', $warehouse_id);
    }

    public function updatedStatus($value): void
    {
        $this->paid_amount = $value === PurchaseStatus::COMPLETED->value ? $this->total_amount : 0;
    }
}
