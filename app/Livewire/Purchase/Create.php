<?php

declare(strict_types=1);

namespace App\Livewire\Purchase;

use App\Enums\PurchaseStatus;
use App\Livewire\Forms\PurchaseForm;
use App\Livewire\Utils\WithModels;
use App\Services\PurchaseService;
use App\Traits\LivewireCartTrait;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Create Purchase')]
class Create extends Component
{
    use LivewireCartTrait;
    use WithAlert;
    use WithModels;

    public PurchaseForm $form;

    public function mount(string $cartInstance = 'purchase'): void
    {
        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        $this->form->payment_method = 'cash';
        $this->form->date = date('Y-m-d');

        if (settings()->default_warehouse_id !== null) {
            $this->form->warehouse_id = settings()->default_warehouse_id;
        }
    }

    public function render()
    {
        // abort_if(Gate::denies('purchase_create'), 403);

        return view('livewire.purchase.create', [
            'cart_items' => $this->cartContent,
        ]);
    }

    public function hydrate(): void
    {
        $this->form->total_amount = $this->calculateTotal();
    }

    public function proceed(): void
    {
        if ($this->form->supplier_id !== null) {
            $this->store();
        } else {
            $this->alert('error', __('Please select a supplier!'));
        }
    }

    public function saveDraft(): void
    {
        $purchaseService = app(PurchaseService::class);

        if (! $this->form->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        if (! $this->form->supplier_id) {
            $this->alert('error', __('Please select a supplier!'));

            return;
        }

        $this->form->validate();

        $purchase = $purchaseService->create(
            [
                'date' => $this->form->date,
                'supplier_id' => $this->form->supplier_id,
                'warehouse_id' => $this->form->warehouse_id,
                'user_id' => Auth::id(),
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
            true // isDraft
        );

        $this->alert('success', __('Draft saved successfully!'));

        $this->clearCart();

        $this->redirectRoute('purchases.index', navigate: true);
    }

    public function store(): void
    {
        $purchaseService = app(PurchaseService::class);

        if (! $this->form->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        $this->form->validate();

        $purchaseService->create(
            [
                'date' => $this->form->date,
                'supplier_id' => $this->form->supplier_id,
                'warehouse_id' => $this->form->warehouse_id,
                'user_id' => Auth::id(),
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
        );

        $this->alert('success', __('Purchase created successfully!'));

        $this->clearCart();

        $this->redirectRoute('purchases.index', navigate: true);
    }

    public function calculateTotal(): float|int|array
    {
        return $this->cartTotal + $this->form->shipping_amount;
    }

    public function resetCart(): void
    {
        $this->clearCart();
    }

    public function updatedFormWarehouseId($warehouse_id): void
    {
        $this->form->warehouse_id = $warehouse_id;
        $this->dispatch('warehouseSelected', $warehouse_id);
    }

    public function updatedFormStatus($status): void
    {
        if ($status === (string) PurchaseStatus::COMPLETED->value || $status === PurchaseStatus::COMPLETED->value) {
            $this->form->paid_amount = $this->form->total_amount;
        }
    }

    public function updatedFormPaymentMethod($payment_status): void
    {
        if ($payment_status === 'cash') {
            $this->form->paid_amount = $this->form->total_amount;
        }
    }
}
