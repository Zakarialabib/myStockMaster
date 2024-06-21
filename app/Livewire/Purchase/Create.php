<?php

declare(strict_types=1);

namespace App\Livewire\Purchase;

use App\Enums\MovementType;
use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;
use App\Jobs\UpdateProductCostHistory;
use App\Livewire\Utils\WithModels;
use App\Models\Movement;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchasePayment;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]

class Create extends Component
{
    use LivewireAlert;
    use WithModels;
    public $cart_instance = 'purchase';

    public $cart_item;

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

    #[Validate('required')]
    public $status;

    #[Validate('required|string|max:50')]
    public $payment_method;

    public $payment_status;

    #[Validate('nullable|string|max:1000')]
    public $note;

    public $product;

    public $quantity;

    public $check_quantity;

    public $price;

    public $date;

    public $discount_type;

    public $item_discount;

    public $listsForFields = [];

    public function mount(): void
    {
        Cart::instance('purchase')->destroy();

        $this->tax_percentage = 0;
        $this->discount_percentage = 0;
        $this->shipping_amount = 0;
        $this->paid_amount = 0;
        $this->payment_method = 'cash';
        $this->date = date('Y-m-d');

        if (settings()->default_warehouse_id !== null) {
            $this->warehouse_id = settings()->default_warehouse_id;
        }
    }

    public function render()
    {
        // abort_if(Gate::denies('purchase_create'), 403);

        $cart_items = Cart::instance($this->cart_instance)->content();

        return view('livewire.purchase.create', [
            'cart_items' => $cart_items,
        ]);
    }

    public function hydrate(): void
    {
        $this->total_amount = $this->calculateTotal();
    }

    public function proceed(): void
    {
        if ($this->supplier_id !== null) {
            $this->store();
        } else {
            $this->alert('error', __('Please select a supplier!'));
        }
    }

    public function store(): void
    {
        if ( ! $this->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        DB::transaction(function () {
            $this->validate();

            $due_amount = $this->total_amount - $this->paid_amount;

            if ($due_amount === $this->total_amount) {
                $this->payment_status = PaymentStatus::PENDING;
                $this->status = PurchaseStatus::PENDING;
            } elseif ($due_amount > 0) {
                $this->payment_status = PaymentStatus::PARTIAL;
                $this->status = PurchaseStatus::PENDING;
            } else {
                $this->payment_status = PaymentStatus::PAID;
                $this->status = PurchaseStatus::COMPLETED;
            }

            $purchase = Purchase::create([
                'date'                => $this->date,
                'supplier_id'         => $this->supplier_id,
                'warehouse_id'        => $this->warehouse_id,
                'user_id'             => Auth::user()->id,
                'tax_percentage'      => $this->tax_percentage,
                'discount_percentage' => $this->discount_percentage,
                'shipping_amount'     => $this->shipping_amount * 100,
                'paid_amount'         => $this->paid_amount * 100,
                'total_amount'        => $this->total_amount * 100,
                'due_amount'          => $due_amount * 100,
                'status'              => $this->status,
                'payment_status'      => $this->payment_status,
                'payment_method'      => $this->payment_method,
                'note'                => $this->note,
                'tax_amount'          => Cart::instance('purchase')->tax() * 100,
                'discount_amount'     => Cart::instance('purchase')->discount() * 100,
            ]);

            foreach (Cart::instance('purchase')->content() as $cart_item) {
                PurchaseDetail::create([
                    'purchase_id'             => $purchase->id,
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

                // UpdateProductCostHistory::dispatch($cart_item);
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
                $new_cost = $product_warehouse->cost;

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

            if ($purchase->paid_amount > 0) {
                PurchasePayment::create([
                    'date'           => date('Y-m-d'),
                    'user_id'        => Auth::user()->id,
                    'amount'         => $purchase->paid_amount,
                    'purchase_id'    => $purchase->id,
                    'payment_method' => $this->payment_method,
                ]);
            }

            $this->alert('success', __('Purchase created successfully!'));

            Cart::instance('purchase')->destroy();

            return redirect()->route('purchases.index');
        });
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

    public function updatedStatus($status): void
    {
        if ($status === PurchaseStatus::COMPLETED) {
            $this->paid_amount = $this->total_amount;
        }
    }

    public function updatedPaymentMethod($payment_status): void
    {
        if ($payment_status === 'cash') {
            $this->paid_amount = $this->total_amount;
        }
    }
}
