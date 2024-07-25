<?php

declare(strict_types=1);

namespace App\Livewire\Sales;

use App\Enums\MovementType;
use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Jobs\PaymentNotification;
use App\Livewire\Utils\WithModels;
use App\Models\CashRegister;
use App\Livewire\CashRegister\Create as CashRegisterCreate;
use App\Models\Category;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\SalePayment;
use App\Models\ProductWarehouse;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class Create extends Component
{
    use LivewireAlert;
    use WithModels;

    public $cart_instance = 'sale';

    public $global_discount;

    public $discount_amount;

    public $global_tax;

    public $quantity;

    public $check_quantity;

    public $discount_type;

    public $item_discount;

    public $date;

    public $price;

    #[Validate('required', message: 'Please provide a customer ID')]
    public $customer_id;

    #[Validate('required', message: 'Please provide a warehouse ID')]
    public $warehouse_id;

    #[Validate('required', message: 'Please provide a tax percentage')]
    #[Validate('integer', message: 'The tax percentage must be an integer')]
    #[Validate('min:0', message: 'The tax percentage must be at least 0')]
    #[Validate('max:100', message: 'The tax percentage must not exceed 100')]
    public $tax_percentage;

    #[Validate('required', message: 'Please provide a discount percentage')]
    #[Validate('integer', message: 'The discount percentage must be an integer')]
    #[Validate('min:0', message: 'The discount percentage must be at least 0')]
    #[Validate('max:100', message: 'The discount percentage must not exceed 100')]
    public $discount_percentage;

    #[Validate('nullable', message: 'Shipping amount must be a numeric value')]
    public $shipping_amount;

    #[Validate('required', message: 'Please provide a total amount')]
    #[Validate('numeric', message: 'The total amount must be a numeric value')]
    public $total_amount;

    #[Validate('nullable', message: 'Paid amount must be a numeric value')]
    public $paid_amount;

    #[Validate('nullable', message: 'Note must be a string with a maximum length of 1000')]
    #[Validate('string', message: 'Note must be a string')]
    #[Validate('max:1000', message: 'Note must not exceed 1000 characters')]
    public $note;

    #[Validate('required|integer|max:255')]
    public $status;

    public $payment_method = 'cash';

    public $cash_register_id;

    public $user_id;

    public function mount(): void
    {
        abort_if(Gate::denies('sale_create'), 403);

        Cart::instance('sale')->destroy();

        // $this->cart_instance = $cartInstance;
        $this->discount_percentage = 0;
        $this->tax_percentage = 0;
        $this->shipping_amount = 0;
        $this->check_quantity = [];
        $this->quantity = [];
        $this->discount_type = [];
        $this->item_discount = [];
        $this->payment_method = 'cash';
        $this->date = date('Y-m-d');
        $this->user_id = Auth::user()->id;

        if (settings()->default_client_id !== null) {
            $this->customer_id = settings()->default_client_id;
        }

        if (settings()->default_warehouse_id !== null) {
            $this->warehouse_id = settings()->default_warehouse_id;
        }

        if ($this->user_id && $this->warehouse_id) {
            $cashRegister = CashRegister::where('user_id', $this->user_id)
                ->where('warehouse_id', $this->warehouse_id)
                ->where('status', true)
                ->first();

            if ($cashRegister) {
                $this->cash_register_id = $cashRegister->id;
            } else {
                $this->dispatch('createModal')->to(CashRegisterCreate::class);

                return;
            }
        }
    }

    public function hydrate(): void
    {
        if ($this->payment_method === 'cash') {
            $this->paid_amount = $this->total_amount;
        }

        $this->total_amount = $this->calculateTotal();
    }

    public function render()
    {
        $cart_items = Cart::instance($this->cart_instance)->content();

        return view('livewire.sales.create', [
            'cart_items' => $cart_items,
        ]);
    }

    public function proceed(): void
    {
        if ($this->user_id && $this->warehouse_id) {
            $cashRegister = CashRegister::where('user_id', $this->user_id)
                ->where('warehouse_id', $this->warehouse_id)
                ->where('status', true)
                ->first();

            if ($cashRegister) {
                $this->cash_register_id = $cashRegister->id;

                $this->store();
            } else {
                $this->dispatch('createModal')->to(CashRegisterCreate::class);

                $this->alert('error', __('Please create a cash register for this warehouse!'));
            }
        }
    }

    public function store(): void
    {
        if ( ! $this->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        if ( ! $this->customer_id) {
            $this->alert('error', __('Please select a customer!'));
        }

        DB::transaction(function () {
            $this->validate();

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

            $sale = Sale::create([
                'date'                => $this->date,
                'customer_id'         => $this->customer_id,
                'warehouse_id'        => $this->warehouse_id,
                'user_id'             => $this->user_id,
                'cash_register_id'    => $this->cash_register_id,
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

            // foreach ($this->cart_instance as cart_items) {}
            foreach (Cart::instance('sale')->content() as $cart_item) {
                SaleDetails::create([
                    'sale_id'                 => $sale->id,
                    'warehouse_id'            => $this->warehouse_id,
                    'product_id'              => $cart_item->id,
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

                $new_quantity = $product_warehouse->qty - $cart_item->qty;

                $product_warehouse->update([
                    'qty' => $new_quantity,
                ]);

                $movement = new Movement([
                    'type'         => MovementType::SALE,
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

            if ($this->paid_amount > 0) {
                SalePayment::create([
                    'date'             => date('Y-m-d'),
                    'amount'           => $this->paid_amount * 100,
                    'sale_id'          => $sale->id,
                    'payment_method'   => $this->payment_method,
                    'cash_register_id' => $this->cash_register_id,
                    'user_id'          => Auth::user()->id,
                ]);
            }

            $this->alert('success', __('Sale created successfully!'));

            Cart::instance('sale')->destroy();

            // dispatch the Send Payment Notification job
            PaymentNotification::dispatch($sale);

            return redirect()->route('sales.index');
        });
    }

    public function calculateTotal(): float|int|array
    {
        return Cart::instance($this->cart_instance)->total() + $this->shipping_amount;
    }

    public function resetCart(): void
    {
        Cart::instance($this->cart_instance)->destroy();
    }

    #[Computed]
    public function category()
    {
        return Category::select('name', 'id')->get();
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
