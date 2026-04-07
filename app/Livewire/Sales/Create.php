<?php

declare(strict_types=1);

namespace App\Livewire\Sales;

use App\Enums\SaleStatus;
use App\Jobs\PaymentNotification;
use App\Livewire\CashRegister\Create as CashRegisterCreate;
use App\Livewire\Forms\SaleForm;
use App\Livewire\Utils\WithModels;
use App\Models\CashRegister;
use App\Services\SaleService;
use App\Traits\LivewireCartTrait;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Create Sale')]
class Create extends Component
{
    use LivewireCartTrait;
    use WithAlert;
    use WithModels;

    public SaleForm $form;

    public int|string|null $user_id = null;

    public int|string|null $cash_register_id = null;

    public function mount(string $cartInstance = 'sale', mixed $quotationId = null): void
    {
        abort_if(Gate::denies('sale_create'), 403);

        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        $this->form->payment_method = 'cash';
        $this->form->date = date('Y-m-d');

        $this->user_id = Auth::user()->id;

        if ($quotationId) {
            $quotation = \App\Models\Quotation::query()->findOrFail($quotationId);
            $this->form->customer_id = $quotation->customer_id;
            $this->form->warehouse_id = $quotation->warehouse_id;
            $this->form->tax_percentage = $quotation->tax_percentage;
            $this->form->discount_percentage = $quotation->discount_percentage;
            $this->form->shipping_amount = $quotation->shipping_amount / 100;
            $this->form->note = $quotation->note;
        } else {
            if (settings()->default_client_id !== null) {
                $this->form->customer_id = settings()->default_client_id;
            }

            if (settings()->default_warehouse_id !== null) {
                $this->form->warehouse_id = settings()->default_warehouse_id;
            }
        }

        if ($this->user_id && $this->form->warehouse_id) {
            $cashRegister = CashRegister::query()->where('user_id', $this->user_id)
                ->where('warehouse_id', $this->form->warehouse_id)
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
        if ($this->form->payment_method === 'cash') {
            $this->form->paid_amount = $this->form->total_amount;
        }

        $this->form->total_amount = $this->calculateTotal();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.sales.create', [
            'cart_items' => $this->cartContent,
        ]);
    }

    public function proceed(): void
    {
        if ($this->user_id && $this->form->warehouse_id) {
            $cashRegister = CashRegister::query()->where('user_id', $this->user_id)
                ->where('warehouse_id', $this->form->warehouse_id)
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

    public function saveDraft(): void
    {
        $saleService = resolve(SaleService::class);

        if (! $this->form->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        if (! $this->form->customer_id) {
            $this->alert('error', __('Please select a customer!'));

            return;
        }

        $this->form->validate();

        $saleService->create(
            [
                'date' => $this->form->date,
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
                'tax_amount' => (int) ($this->cartTax * 100),
                'discount_amount' => (int) ($this->cartDiscount * 100),
                'status' => $this->form->status,
                'payment_status' => $this->form->payment_status,
            ],
            $this->cartContent->toArray(),
            $this->cartTax,
            $this->cartDiscount,
            true // isDraft = true
        );

        $this->alert('success', __('Draft saved successfully!'));

        $this->clearCart();

        $this->redirectRoute('sales.index', navigate: true);
    }

    public function store(): void
    {
        $saleService = resolve(SaleService::class);

        if (! $this->form->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        if (! $this->form->customer_id) {
            $this->alert('error', __('Please select a customer!'));

            return;
        }

        $this->form->validate();

        $sale = $saleService->create(
            [
                'date' => $this->form->date,
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
                'tax_amount' => (int) ($this->cartTax * 100),
                'discount_amount' => (int) ($this->cartDiscount * 100),
                'status' => $this->form->status,
                'payment_status' => $this->form->payment_status,
            ],
            $this->cartContent->toArray(),
            $this->cartTax,
            $this->cartDiscount
        );

        $this->alert('success', __('Sale created successfully!'));

        $this->clearCart();

        dispatch(new \App\Jobs\PaymentNotification($sale));

        $this->redirectRoute('sales.index', navigate: true);
    }

    public function calculateTotal(): float|int|array
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
        $this->dispatch('warehouseSelected', $this->form->warehouse_id);
    }

    public function updatedFormStatus(mixed $value): void
    {
        if ($value === (string) SaleStatus::COMPLETED->value || $value === SaleStatus::COMPLETED->value) {
            $this->form->paid_amount = $this->form->total_amount;
        }
    }
}
