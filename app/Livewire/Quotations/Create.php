<?php

declare(strict_types=1);

namespace App\Livewire\Quotations;

use App\Livewire\Forms\QuotationForm;
use App\Livewire\Utils\WithModels;
use App\Models\Quotation;
use App\Models\QuotationDetails;
use App\Traits\LivewireCartTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
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

    public function store()
    {
        if (! $this->form->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        DB::transaction(function (): void {
            $this->form->validate();

            $quotation = Quotation::create([
                'date' => $this->form->date,
                'customer_id' => $this->form->customer_id,
                'warehouse_id' => $this->form->warehouse_id,
                'user_id' => Auth::user()->id,
                'tax_percentage' => $this->form->tax_percentage,
                'discount_percentage' => $this->form->discount_percentage,
                'shipping_amount' => $this->form->shipping_amount * 100,
                'total_amount' => $this->form->total_amount * 100,
                'status' => $this->form->status,
                'note' => $this->form->note,
                'tax_amount' => (int) $this->cartTax * 100,
                'discount_amount' => (int) $this->cartDiscount * 100,
            ]);

            foreach ($this->cartContent as $cart_item) {
                QuotationDetails::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $cart_item->id,
                    'name' => $cart_item->name,
                    'code' => $cart_item->options->code,
                    'quantity' => $cart_item->qty,
                    'price' => $cart_item->price * 100,
                    'unit_price' => $cart_item->options->unit_price * 100,
                    'sub_total' => $cart_item->options->sub_total * 100,
                    'product_discount_amount' => $cart_item->options->product_discount * 100,
                    'product_discount_type' => $cart_item->options->product_discount_type,
                    'product_tax_amount' => $cart_item->options->product_tax * 100,
                ]);
            }

            $this->clearCart();
        });

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
