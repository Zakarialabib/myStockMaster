<?php

declare(strict_types=1);

namespace App\Livewire\PurchaseReturn;

use App\Actions\Purchases\StorePurchaseReturnAction;
use App\Livewire\Forms\PurchaseReturnForm;
use App\Models\Supplier;
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

    public PurchaseReturnForm $form;

    public $reference;

    public function mount(string $cartInstance = 'purchase_return'): void
    {
        abort_if(Gate::denies('purchase_return_create'), 403);

        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        $this->form->tax_percentage = 0;
        $this->form->discount_percentage = 0;
        $this->form->shipping_amount = 0;
        $this->form->paid_amount = 0;
        $this->form->payment_method = 'Cash';
        $this->form->status = 'Pending';
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
        if ($this->form->supplier_id !== null) {
            $this->store();
        } else {
            $this->alert('error', __('Please select a supplier!'));
        }
    }

    public function store(): void
    {
        abort_if(Gate::denies('purchase_return_create'), 403);

        $this->form->validate();

        app(StorePurchaseReturnAction::class)(
            [
                'date' => $this->form->date,
                'reference' => $this->reference,
                'supplier_id' => $this->form->supplier_id,
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

        $this->alert('success', __('Purchase Return created successfully.'));
        $this->redirectRoute('purchase-returns.index', navigate: true);
    }

    public function getSuppliersProperty()
    {
        return Supplier::select(['id', 'name'])->get()->map(function ($supplier) {
            return [
                'label' => $supplier->name,
                'value' => $supplier->id,
            ];
        })->toArray();
    }

    public function getWarehousesProperty()
    {
        return \App\Models\Warehouse::pluck('name', 'id')->toArray();
    }

    public function render()
    {
        return view('livewire.purchase-return.create');
    }
}
