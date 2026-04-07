<?php

declare(strict_types=1);

namespace App\Livewire\Pos;

use App\Actions\Sales\StorePosSaleAction;
use App\Jobs\PaymentNotification;
use App\Jobs\PrintReceiptJob;
use App\Livewire\Forms\PosCheckoutForm;
use App\Livewire\Utils\WithModels;
use App\Models\CashRegister;
use App\Traits\LivewireCartTrait;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Isolate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Isolate]
#[Layout('layouts.pos')]
#[Title('Point of Sale')]
class Index extends Component
{
    use LivewireCartTrait;
    use WithAlert;
    use WithModels;

    public PosCheckoutForm $form;

    public bool $discountModal = false;

    public float|int $global_discount = 0;

    public float|int $global_tax = 0;

    public bool $checkoutModal = false;

    public float|int $total_with_shipping = 0;

    #[Locked]
    public int|string|null $user_id = null;

    #[Locked]
    public int|string|null $cash_register_id = null;

    public function mount(string $cartInstance = 'pos'): void
    {
        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        if (settings()->default_client_id !== null) {
            $this->form->customer_id = settings()->default_client_id;
        }

        if (settings()->default_warehouse_id !== null) {
            $this->form->warehouse_id = settings()->default_warehouse_id;
        }

        $this->user_id = Auth::user()->id;

        if ($this->user_id && $this->form->warehouse_id) {
            $cashRegister = CashRegister::query()->where('user_id', $this->user_id)
                ->where('warehouse_id', $this->form->warehouse_id)
                ->where('status', true)
                ->first();

            if ($cashRegister) {
                $this->cash_register_id = $cashRegister->id;
            } else {
                $this->initializeCashRegister();
            }
        }

        $this->total_with_shipping = (float) $this->cartTotal + (float) $this->form->shipping_amount;
    }

    protected function initializeCashRegister(): void
    {
        if (! $this->user_id || ! $this->form->warehouse_id) {
            return;
        }

        $cashRegister = CashRegister::query()->create([
            'user_id' => $this->user_id,
            'warehouse_id' => $this->form->warehouse_id,
            'cash_in_hand' => 0,
            'status' => true,
        ]);

        $this->cash_register_id = $cashRegister->id;

        $this->dispatch('cash-register-opened', cashRegisterId: $cashRegister->id);
    }

    public function syncCartState(): void
    {
        $this->form->total_amount = $this->calculateTotal();
        if ($this->form->payment_method === 'cash') {
            $this->form->paid_amount = $this->form->total_amount;
        }
    }

    public function updatedFormShippingAmount(): void
    {
        $this->form->total_amount = $this->calculateTotal();
        $this->total_with_shipping = $this->form->total_amount;
        if ($this->form->payment_method === 'cash') {
            $this->form->paid_amount = $this->form->total_amount;
        }
    }

    public function updatedFormPaymentMethod(mixed $value): void
    {
        if ($value === 'cash') {
            $this->form->paid_amount = $this->form->total_amount;
        }
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.pos.index', [
            'cart_items' => $this->cartContent,
        ]);
    }

    public function store(): void
    {
        if (! $this->form->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        $this->form->validate();

        $sale = resolve(StorePosSaleAction::class)(
            [
                'date' => date('Y-m-d'),
                'customer_id' => $this->form->customer_id,
                'warehouse_id' => $this->form->warehouse_id,
                'user_id' => $this->user_id,
                'cash_register_id' => $this->cash_register_id,
                'tax_percentage' => $this->form->tax_percentage,
                'discount_percentage' => $this->form->discount_percentage,
                'shipping_amount' => $this->form->shipping_amount,
                'paid_amount' => $this->form->paid_amount,
                'total_amount' => $this->form->total_amount,
                'payment_method' => $this->form->payment_method,
                'note' => $this->form->note,
            ],
            $this->cartContent,
            $this->cartTax,
            $this->cartDiscount,
        );

        $this->clearCart();
        $this->alert('success', __('Sale created successfully!'));
        $this->checkoutModal = false;

        dispatch(new \App\Jobs\PaymentNotification($sale));

        // Dispatch physical print job if applicable
        dispatch(new \App\Jobs\PrintReceiptJob($sale->id));

        // Tell browser to open PDF receipt
        $this->dispatch('open-print-window', url: route('sales.pos.pdf', $sale->id));

        $this->redirectRoute('pos.index', navigate: true);
    }

    #[On('printReceipt')]
    public function printReceipt(string $saleId): void
    {
        dispatch(new \App\Jobs\PrintReceiptJob($saleId));
        $this->dispatch('open-print-window', url: route('sales.pos.pdf', $saleId));
    }

    public function proceed(): void
    {
        if ($this->cartCount === 0) {
            $this->alert('error', __('Please add products to cart!'));

            return;
        }

        if ($this->form->customer_id !== null) {
            $this->checkoutModal = true;
        } else {
            $this->alert('error', __('Please select a customer!'));
        }
    }

    public function calculateTotal(): mixed
    {
        return $this->cartTotal + $this->form->shipping_amount;
    }

    public function resetCart(): void
    {
        $this->clearCart();
    }

    public function updatedFormWarehouseId(mixed $value): void
    {
        $this->form->warehouse_id = $value;
        $this->dispatch('warehouseSelected', warehouseId: (int) $value);
    }
}
