<?php

declare(strict_types=1);

namespace App\Livewire\Pos;

use App\Actions\Sales\StorePosSaleAction;
use App\Jobs\PaymentNotification;
use App\Livewire\Utils\WithModels;
use App\Models\CashRegister;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Livewire\CashRegister\Create as CashRegisterCreate;
use App\Traits\LivewireCartTrait;
use App\Traits\WithAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.pos')]
class Index extends Component
{
    use WithAlert;
    use WithModels;
    use LivewireCartTrait;

    public $customers;

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
        // Clear any existing cart content
        $this->clearCart();

        $this->customers = Customer::select(['id', 'name'])->get();
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

        $this->total_with_shipping = (float) $this->cart->total() + (float) $this->shipping_amount;
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
        $cart_items = $this->cart->content();

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

        $this->validate();

        $sale = app(StorePosSaleAction::class)(
            [
                'date'                => date('Y-m-d'),
                'customer_id'         => $this->customer_id,
                'warehouse_id'        => $this->warehouse_id,
                'user_id'             => $this->user_id,
                'cash_register_id'    => $this->cash_register_id,
                'tax_percentage'      => $this->tax_percentage,
                'discount_percentage' => $this->discount_percentage,
                'shipping_amount'     => $this->shipping_amount,
                'paid_amount'         => $this->paid_amount,
                'total_amount'        => $this->total_amount,
                'payment_method'      => $this->payment_method,
                'note'                => $this->note,
            ],
            $this->cart->content(),
            $this->cart->tax(),
            $this->cart->discount(),
        );

        $this->clearCart();
        $this->alert('success', __('Sale created successfully!'));
        $this->checkoutModal = false;

        PaymentNotification::dispatch($sale);

        $this->redirectRoute('pos.index', navigate: true);
    }

    public function proceed(): void
    {
        if ($this->customer_id !== null) {
            $this->checkoutModal = true;
        } else {
            $this->alert('error', __('Please select a customer!'));
        }
    }

    public function calculateTotal(): mixed
    {
        return $this->cart->total() + $this->shipping_amount;
    }

    public function resetCart(): void
    {
        $this->clearCart();
    }

    public function updatedWarehouseId($value): void
    {
        $this->warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->warehouse_id);
    }
}
