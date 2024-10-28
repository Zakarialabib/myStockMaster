<?php

declare(strict_types=1);

namespace App\Livewire\Pos;

use App\Enums\MovementType;
use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Jobs\PaymentNotification;
use App\Livewire\Utils\WithModels;
use App\Models\CashRegister;
use App\Models\Customer;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use App\Models\ProductWarehouse;
use App\Models\SaleDetails;
use App\Models\SalePayment;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Livewire\CashRegister\Create as CashRegisterCreate;
use Livewire\Attributes\Validate;

#[Layout('layouts.pos')]
class Index extends Component
{
    use LivewireAlert;
    use WithModels;

    public $customers;

    public $cart_instance = 'sale';

    public $discountModal;

    public $global_discount;

    public $global_tax;

    public $quantity;

    public $check_quantity;

    public $price;

    public $discount_type;

    public $item_discount;

    public $data;

    public $checkoutModal;

    public $product;

    public $discount_amount;

    public $tax_amount;

    public $payment_method;

    public $total_with_shipping;

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

    public $user_id;

    public $cash_register_id;

    public function mount(): void
    {
        Cart::instance('sale')->destroy();

        $this->customers = Customer::select(['id','name'])->get();
        $this->global_discount = 0;
        $this->global_tax = 0;

        $this->check_quantity = [];
        $this->quantity = [];
        $this->discount_type = [];
        $this->item_discount = [];
        $this->payment_method = 'cash';

        $this->tax_percentage = 0;
        $this->discount_percentage = 0;
        $this->shipping_amount = 0;
        $this->paid_amount = 0;

        if (settings()->default_client_id !== null) {
            $this->customer_id = settings()->default_client_id;
        }

        if (settings()->default_warehouse_id !== null) {
            $this->warehouse_id = settings()->default_warehouse_id;
        }

        $this->user_id = Auth::user()->id;

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

        $this->total_with_shipping = (float) Cart::instance($this->cart_instance)->total() + (float) $this->shipping_amount;
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

        return view('livewire.pos.index', [
            'cart_items' => $cart_items,
        ]);
    }

    public function store(): void
    {
        if ( ! $this->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        DB::transaction(function () {
            $this->validate();

            // Determine payment status
            $due_amount = $this->total_amount - $this->paid_amount;

            if ($due_amount === $this->total_amount) {
                $payment_status = PaymentStatus::PENDING;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::PARTIAL;
            } else {
                $payment_status = PaymentStatus::PAID;
            }

            $sale = Sale::create([
                'date'                => date('Y-m-d'),
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
                'status'              => SaleStatus::COMPLETED,
                'payment_status'      => $payment_status,
                'payment_method'      => $this->payment_method,
                'note'                => $this->note,
                'tax_amount'          => (int) Cart::instance('sale')->tax() * 100,
                'discount_amount'     => (int) Cart::instance('sale')->discount() * 100,
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
                    'amount'           => $this->paid_amount,
                    'cash_register_id' => $this->cash_register_id,
                    'sale_id'          => $sale->id,
                    'payment_method'   => $this->payment_method,
                    'user_id'          => Auth::user()->id,
                ]);
            }

            $this->alert('success', __('Sale created successfully!'));

            $this->checkoutModal = false;

            Cart::instance('sale')->destroy();

            PaymentNotification::dispatch($sale);

            return redirect()->route('pos.index');
        });
    }

    public function proceed(): void
    {
        if ($this->customer_id !== null) {
            $this->checkoutModal = true;
            $this->cart_instance = 'sale';
        } else {
            $this->alert('error', __('Please select a customer!'));
        }
    }

    public function calculateTotal(): mixed
    {
        return Cart::instance($this->cart_instance)->total() + $this->shipping_amount;
    }

    public function resetCart(): void
    {
        Cart::instance($this->cart_instance)->destroy();
    }

    public function updatedWarehouseId($value): void
    {
        $this->warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->warehouse_id);
    }
}
