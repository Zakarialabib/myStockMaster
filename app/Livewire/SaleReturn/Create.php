<?php

declare(strict_types=1);

namespace App\Livewire\SaleReturn;

use App\Actions\Sales\StoreSaleReturnAction;
use App\Livewire\Forms\SaleReturnForm;
use App\Models\Customer;
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
        $this->form->status = 'Pending';
        $this->form->date = date('Y-m-d');
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

        $this->form->validate();

        app(StoreSaleReturnAction::class)(
            [
                'date' => $this->form->date,
                'reference' => $this->form->reference,
                'customer_id' => $this->form->customer_id,
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

    public function render()
    {
        $customers = Customer::select(['id', 'name'])->get();

        return view('livewire.sale-return.create', [
            'cart_items' => $this->cartContent,
            'customers' => $customers,
        ]);
    }
}
