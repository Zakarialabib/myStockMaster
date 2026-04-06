<?php

declare(strict_types=1);

namespace App\Livewire\SaleReturn;

use App\Services\SaleReturnService;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SaleReturn;
use App\Traits\LivewireCartTrait;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use App\Livewire\Forms\SaleReturnForm;
use Livewire\Component;

#[Layout('layouts.app')]
class Edit extends Component
{
    use LivewireCartTrait;
    use WithAlert;

    public SaleReturnForm $form;

    public $salereturn;

    public function mount($id, string $cartInstance = 'sale_return'): void
    {
        abort_if(Gate::denies('sale_return_update'), 403);

        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        $this->salereturn = SaleReturn::findOrFail($id);

        $this->form->customer_id = $this->salereturn->customer_id;
        $this->form->reference = $this->salereturn->reference;
        $this->form->tax_percentage = $this->salereturn->tax_percentage;
        $this->form->discount_percentage = $this->salereturn->discount_percentage;
        $this->form->shipping_amount = $this->salereturn->shipping_amount / 100;
        $this->form->total_amount = $this->salereturn->total_amount / 100;
        $this->form->paid_amount = $this->salereturn->paid_amount / 100;
        $this->form->status = $this->salereturn->status;
        $this->form->payment_method = $this->salereturn->payment_method;
        $this->form->note = $this->salereturn->note;
        $this->form->date = $this->salereturn->date;

        $this->clearCart();

        foreach ($this->salereturn->saleReturnDetails as $detail) {
            $product = Product::findOrFail($detail->product_id);
            $this->addToCart([
                'id' => $detail->product_id,
                'name' => $detail->name,
                'quantity' => $detail->quantity,
                'price' => $detail->price / 100,
                'attributes' => [
                    'product_discount' => $detail->discount_amount / 100,
                    'product_discount_type' => $detail->discount_type,
                    'sub_total' => $detail->sub_total / 100,
                    'code' => $detail->code,
                    'stock' => $product->quantity,
                    'product_tax' => $detail->tax_amount / 100,
                    'unit_price' => $detail->unit_price / 100,
                ],
            ]);
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
            $this->update();
        } else {
            $this->alert('error', __('Please select a customer!'));
        }
    }

    public function update(): void
    {
        abort_if(Gate::denies('sale_return_update'), 403);

        $this->form->validate();

        app(SaleReturnService::class)->update(
            $this->salereturn,
            [
                'date' => $this->form->date,
                'reference' => $this->form->reference,
                'customer_id' => $this->form->customer_id,
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

        $this->alert('success', __('Sale Return updated successfully.'));
        $this->redirectRoute('sale-returns.index', navigate: true);
    }

    public function render()
    {
        $customers = Customer::select(['id', 'name'])->get();

        return view('livewire.sale-return.edit', [
            'cart_items' => $this->cartContent,
            'customers' => $customers,
        ]);
    }
}
