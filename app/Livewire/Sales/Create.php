<?php

declare(strict_types=1);

namespace App\Livewire\Sales;

use App\Actions\Sales\StoreSaleAction;
use App\Enums\SaleStatus;
use App\Jobs\PaymentNotification;
use App\Livewire\Utils\WithModels;
use App\Models\CashRegister;
use App\Livewire\CashRegister\Create as CashRegisterCreate;
use App\Models\Category;
use App\Traits\LivewireCartTrait;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Create Sale')]
class Create extends Component
{
    use WithModels;
    use LivewireCartTrait;

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

    public string $payment_method = 'cash';

    public $cash_register_id;

    public $user_id;

    public function mount(string $cartInstance = 'sale', $quotationId = null): void
    {
        abort_if(Gate::denies('sale_create'), 403);

        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

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

        if ($quotationId) {
            $quotation = \App\Models\Quotation::findOrFail($quotationId);
            $this->customer_id = $quotation->customer_id;
            $this->warehouse_id = $quotation->warehouse_id;
            $this->tax_percentage = $quotation->tax_percentage;
            $this->discount_percentage = $quotation->discount_percentage;
            $this->shipping_amount = $quotation->shipping_amount / 100;
            $this->note = $quotation->note;
        } else {
            if (settings()->default_client_id !== null) {
                $this->customer_id = settings()->default_client_id;
            }

            if (settings()->default_warehouse_id !== null) {
                $this->warehouse_id = settings()->default_warehouse_id;
            }
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
        return view('livewire.sales.create', [
            'cart_items' => $this->cartContent,
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

        $this->validate();

        $sale = app(StoreSaleAction::class)(
            [
                'date'                => $this->date,
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
                'tax_amount'          => (int) ($this->cartTax * 100),
                'discount_amount'     => (int) ($this->cartDiscount * 100),
            ],
            $this->cartContent->toArray(),
            $this->cartTax,
            $this->cartDiscount
        );

        $this->alert('success', __('Sale created successfully!'));

        $this->clearCart();

        PaymentNotification::dispatch($sale);

        $this->redirectRoute('sales.index', navigate: true);
    }

    public function calculateTotal(): float|int|array
    {
        return $this->cartTotal + $this->shipping_amount;
    }

    public function resetCart(): void
    {
        $this->clearCart();
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
