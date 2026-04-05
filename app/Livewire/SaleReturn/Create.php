<?php

declare(strict_types=1);

namespace App\Livewire\SaleReturn;

use App\Actions\Sales\StoreSaleReturnAction;
use App\Livewire\Forms\SaleReturnForm;
use App\Livewire\Utils\WithModels;
use App\Traits\LivewireCartTrait;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Create extends Component
{
    use LivewireCartTrait;
    use WithAlert;
    use WithModels;

    public SaleReturnForm $form;

    public function mount(string $cartInstance = 'sale_return'): void
    {
        abort_if(Gate::denies('sale_return_create'), 403);

        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        $this->form->tax_percentage = 0;
        $this->form->discount_percentage = 0;
        $this->form->shipping_amount = 0;
        $this->form->paid_amount = 0;
        $this->form->payment_method = 'Cash';
        $this->form->status = \App\Enums\SaleReturnStatus::PENDING->value;
        $this->form->date = date('Y-m-d');

        if (settings()->default_warehouse_id !== null) {
            $this->form->warehouse_id = settings()->default_warehouse_id;
        }
    }

    public function hydrate(): void
    {
        $this->form->total_amount = $this->calculateTotal();
    }

    public function calculateTotal(): float
    {
        return $this->cartTotal + (float) $this->form->shipping_amount;
    }

    public function proceed(): void
    {
        if ($this->form->customer_id !== null) {
            $this->store();
        } else {
            $this->alert('error', __('Please select a customer!'));
        }
    }

    public function store(): void
    {
        abort_if(Gate::denies('sale_return_create'), 403);

        if (! $this->form->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        $this->form->validate();

        app(StoreSaleReturnAction::class)(
            [
                'date' => $this->form->date,
                'customer_id' => $this->form->customer_id,
                'warehouse_id' => $this->form->warehouse_id,
                'user_id' => Auth::id(),
                'tax_percentage' => $this->form->tax_percentage,
                'discount_percentage' => $this->form->discount_percentage,
                'shipping_amount' => $this->form->shipping_amount,
                'paid_amount' => $this->form->paid_amount,
                'total_amount' => $this->form->total_amount,
                'status' => $this->form->status,
                'payment_method' => $this->form->payment_method,
                'note' => $this->form->note,
            ],
            $this->cartContent->toArray(),
            $this->cartTax,
            $this->cartDiscount
        );

        $this->clearCart();

        $this->alert('success', __('Sale Return created successfully.'));
        $this->redirectRoute('sale-returns.index', navigate: true);
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

    public function render()
    {
        return view('livewire.sale-return.create', [
            'cart_items' => $this->cartContent,
        ]);
    }
}
