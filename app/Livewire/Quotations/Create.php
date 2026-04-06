<?php

declare(strict_types=1);

namespace App\Livewire\Quotations;

use App\Livewire\Utils\WithModels;
use App\Models\Quotation;
use App\Models\QuotationDetails;
use App\Services\QuotationService;
use App\Traits\LivewireCartTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Livewire\Forms\QuotationForm;
use Livewire\Component;

#[Layout('layouts.app')]
class Create extends Component
{
    use LivewireCartTrait;
    use WithModels;

    public QuotationForm $form;

    

    

    

    

    

    

    

    

    public function proceed(): void
    {
        if ($this->form->customer_id !== null) {
            $this->store();
        } else {
            $this->alert('error', __('Please select a customer!'));
        }
    }

    public function mount(string $cartInstance = 'quotation'): void
    {
        abort_if(Gate::denies('quotation_create'), 403);

        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        $this->form->discount_percentage = 0;
        $this->form->tax_percentage = 0;
        $this->form->shipping_amount = 0;

        if (settings()->default_client_id !== null) {
            $this->form->customer_id = settings()->default_client_id;
        }

        if (settings()->default_warehouse_id !== null) {
            $this->form->warehouse_id = settings()->default_warehouse_id;
        }

        $this->form->date = date('Y-m-d');
    }

    public function store(QuotationService $quotationService)
    {
        if (! $this->form->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        $this->form->validate();

        $quotationService->create(
            $this->form->all(),
            $this->cartContent,
            $this->cartTax,
            $this->cartDiscount
        );

        $this->clearCart();

        $this->alert('success', __('Quotation created successfully!'));

        return redirect()->route('quotations.index');
    }

    public function hydrate(): void
    {
        $this->form->total_amount = $this->calculateTotal();
    }

    public function calculateTotal(): float|int|array
    {
        return $this->cartTotal + $this->form->shipping_amount;
    }

    public function render(): \Illuminate\View\View
    {
        abort_if(Gate::denies('quotation_create'), 403);

        return view('livewire.quotations.create', ['cart_items' => $this->cartContent]);
    }

    public function updatedFormWarehouseId($value): void
    {
        $this->form->warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->form->warehouse_id);
    }
}
