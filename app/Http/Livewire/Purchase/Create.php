<?php

declare(strict_types=1);

namespace App\Http\Livewire\Purchase;

use App\Enums\MovementType;
use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;
use App\Jobs\UpdateProductCostHistory;
use App\Models\Movement;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\PriceHistory;
use App\Models\ProductWarehouse;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchasePayment;
use App\Models\Supplier;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = [
        'refreshIndex' => '$refresh',
    ];

    public $cart_instance;
    public $cart_item;

    public $supplier_id;

    public $supplier;

    public $product;

    public $quantity;

    public $warehouse_id;

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

    public $payment_status;

    public $date;

    public $discount_type;

    public $item_discount;

    public $listsForFields = [];

    public function rules(): array
    {
        return [
            'warehouse_id'        => 'required|integer',
            'supplier_id'         => 'required|integer',
            'tax_percentage'      => 'required|integer|min:0|max:100',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'shipping_amount'     => 'required|numeric',
            'total_amount'        => 'required|numeric',
            'paid_amount'         => 'required|numeric',
            'status'              => 'required|string|max:50',
            'payment_method'      => 'required|string|max:50',
            'note'                => 'nullable|string|max:1000',
        ];
    }

    public function mount($cartInstance): void
    {
        $this->cart_instance = $cartInstance;
        $this->tax_percentage = 0;
        $this->discount_percentage = 0;
        $this->shipping_amount = 0;
        $this->paid_amount = 0;
        $this->payment_method = 'cash';
        $this->date = date('Y-m-d');
        // $this->warehouse_id = "";

        $this->initListsForFields();
    }

    public function render()
    {
        $cart_items = Cart::instance($this->cart_instance)->content();

        return view('livewire.purchase.create', [
            'cart_items' => $cart_items,
        ]);
    }

    public function hydrate(): void
    {
        $this->total_amount = $this->calculateTotal();
    }

    public function proceed()
    {
        if ($this->supplier_id !== null) {
            $this->store();
        } else {
            $this->alert('error', __('Please select a supplier!'));
        }
    }

    public function store()
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

            // dd($this->supplier_id);
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
                    'movable_type' => get_class($product),
                    'movable_id'   => $product->id,
                    'user_id'      => Auth::user()->id,
                ]);

                $movement->save();

                PriceHistory::create([
                    'product_id'   => $cart_item->id,
                    'warehouse_id' => $this->warehouse_id,
                    'cost'         => $new_cost * 100,
                ]);
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

    protected function initListsForFields(): void
    {
        $this->listsForFields['suppliers'] = Supplier::pluck('name', 'id')->toArray();
    }

    public function getWarehousesProperty()
    {
        return  Warehouse::pluck('name', 'id')->toArray();
    }

    public function updatedWarehouseId($warehouse_id)
    {
        $this->warehouse_id = $warehouse_id;
        $this->emit('warehouseSelected', $warehouse_id);
    }

    public function updatedStatus($value)
    {
        if ($value === PurchaseStatus::COMPLETED) {
            $this->paid_amount = $this->total_amount;
        }
    }
}
