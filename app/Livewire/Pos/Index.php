<?php

declare(strict_types=1);

namespace App\Livewire\Pos;

use App\Actions\Sales\StorePosSaleAction;
use App\Jobs\PaymentNotification;
use App\Jobs\PrintReceiptJob;
use App\Livewire\Utils\WithModels;
use App\Models\CashRegister;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Livewire\CashRegister\Create as CashRegisterCreate;
use App\Traits\LivewireCartTrait;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Layout('layouts.pos')]
#[Title('Point of Sale')]
class Index extends Component
{
    use WithAlert;
    use WithModels;
    use LivewireCartTrait;

    public bool $discountModal = false;

    public float|int $global_discount = 0;

    public float|int $global_tax = 0;

    public array $quantity = [];

    public array $check_quantity = [];

    public array $price = [];

    public array $discount_type = [];

    public array $item_discount = [];

    public mixed $data = null;

    public bool $checkoutModal = false;

    public mixed $product = null;

    public float|int $discount_amount = 0;

    public float|int $tax_amount = 0;

    public string $payment_method = 'cash';

    public float|int $total_with_shipping = 0;

    #[Validate('required', message: 'Please provide a customer ID')]
    public int|string|null $customer_id = null;

    #[Validate('required', message: 'Please provide a warehouse ID')]
    public int|string|null $warehouse_id = null;

    #[Validate('required', message: 'Please provide a tax percentage')]
    #[Validate('integer', message: 'The tax percentage must be an integer')]
    #[Validate('min:0', message: 'The tax percentage must be at least 0')]
    #[Validate('max:100', message: 'The tax percentage must not exceed 100')]
    public int $tax_percentage = 0;

    #[Validate('required', message: 'Please provide a discount percentage')]
    #[Validate('integer', message: 'The discount percentage must be an integer')]
    #[Validate('min:0', message: 'The discount percentage must be at least 0')]
    #[Validate('max:100', message: 'The discount percentage must not exceed 100')]
    public int $discount_percentage = 0;

    #[Validate('nullable', message: 'Shipping amount must be a numeric value')]
    public float|int $shipping_amount = 0;

    #[Validate('required', message: 'Please provide a total amount')]
    #[Validate('numeric', message: 'The total amount must be a numeric value')]
    public float|int $total_amount = 0;

    #[Validate('nullable', message: 'Paid amount must be a numeric value')]
    public float|int $paid_amount = 0;

    #[Validate('nullable', message: 'Note must be a string with a maximum length of 1000')]
    #[Validate('string', message: 'Note must be a string')]
    #[Validate('max:1000', message: 'Note must not exceed 1000 characters')]
    public ?string $note = null;

    public int|string|null $user_id = null;

    public int|string|null $cash_register_id = null;

    #[On('refreshIndex')]
    public function refreshCustomers(): void
    {
        unset($this->customers);
    }

    #[Computed]
    public function customers()
    {
        return Customer::select(['id', 'name'])->get();
    }

    public function mount(string $cartInstance = 'pos'): void
    {
        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

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
            }
        }

        $this->total_with_shipping = (float) $this->cartTotal + (float) $this->shipping_amount;
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
        return view('livewire.pos.index', [
            'cart_items' => $this->cartContent,
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
            $this->cartContent,
            $this->cartTax,
            $this->cartDiscount,
        );

        $this->clearCart();
        $this->alert('success', __('Sale created successfully!'));
        $this->checkoutModal = false;

        PaymentNotification::dispatch($sale);

        // Dispatch physical print job if applicable
        PrintReceiptJob::dispatch($sale->id);

        // Tell browser to open PDF receipt
        $this->dispatch('open-print-window', url: route('sales.pos.pdf', $sale->id));

        $this->redirectRoute('pos.index', navigate: true);
    }

    #[On('printReceipt')]
    public function printReceipt(string $saleId): void
    {
        PrintReceiptJob::dispatch($saleId);
        $this->dispatch('open-print-window', url: route('sales.pos.pdf', $saleId));
    }

    public function proceed(): void
    {
        if ($this->cartCount === 0) {
            $this->alert('error', __('Please add products to cart!'));

            return;
        }

        if ($this->customer_id !== null) {
            $this->checkoutModal = true;
        } else {
            $this->alert('error', __('Please select a customer!'));
        }
    }

    public function calculateTotal(): mixed
    {
        return $this->cartTotal + $this->shipping_amount;
    }

    public function resetCart(): void
    {
        $this->clearCart();
    }

    public function updatedWarehouseId($value): void
    {
        $this->warehouse_id = $value;
        $this->dispatch('warehouseSelected', warehouseId: (int) $value);
    }
}
