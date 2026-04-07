<?php

declare(strict_types=1);

namespace App\Livewire\Quotations;

use App\Livewire\Forms\QuotationForm;
use App\Livewire\Utils\WithModels;
use App\Models\Product;
use App\Models\Quotation;
use App\Services\QuotationService;
use App\Traits\LivewireCartTrait;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Edit extends Component
{
    use LivewireCartTrait;
    use WithModels;

    public QuotationForm $form;

    public mixed $quotation;

    public mixed $quotation_details;

    public function mount(mixed $id, string $cartInstance = 'quotation'): void
    {
        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        $this->quotation = Quotation::query()->findOrFail($id);

        $this->quotation_details = $this->quotation->quotationDetails;

        foreach ($this->quotation_details as $quotation_detail) {
            $product = Product::query()->findOrFail($quotation_detail->product_id);
            $this->addToCart([
                'id' => $quotation_detail->product_id,
                'name' => $quotation_detail->name,
                'quantity' => $quotation_detail->quantity,
                'price' => $quotation_detail->price / 100,
                'attributes' => [
                    'product_discount' => $quotation_detail->product_discount_amount / 100,
                    'product_discount_type' => $quotation_detail->product_discount_type,
                    'sub_total' => $quotation_detail->sub_total / 100,
                    'code' => $quotation_detail->code,
                    'stock' => $product->quantity,
                    'unit_price' => $quotation_detail->unit_price / 100,
                ],
            ]);
        }

        $this->form->reference = $this->quotation->reference;
        $this->form->date = $this->quotation->getAttributes()['date'];
        $this->form->customer_id = $this->quotation->customer_id;
        $this->form->warehouse_id = $this->quotation->warehouse_id;
        $this->form->status = $this->quotation->status;
        $this->form->note = $this->quotation->note;
        $this->form->tax_percentage = $this->quotation->tax_percentage;
        $this->form->discount_percentage = $this->quotation->discount_percentage;
        $this->form->shipping_amount = $this->quotation->shipping_amount;
        $this->form->total_amount = $this->quotation->total_amount;
    }

    public function update(QuotationService $quotationService)
    {
        $this->form->validate();

        $quotationService->update(
            $this->quotation,
            $this->form->all(),
            $this->cartContent,
            $this->cartTax,
            $this->cartDiscount
        );

        $this->clearCart();

        $this->alert('success', __('Quotation updated Successfully!'));

        return to_route('quotations.index');
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('quotation update'), 403);

        return view('livewire.quotations.edit');
    }

    public function hydrate(): void
    {
        $this->form->total_amount = $this->calculateTotal();
    }

    public function calculateTotal(): float|int|array
    {
        return $this->cartTotal + $this->form->shipping_amount;
    }

    public function updatedFormWarehouseId(mixed $value): void
    {
        $this->form->warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->form->warehouse_id);
    }
}
