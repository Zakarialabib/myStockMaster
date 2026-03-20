<?php

declare(strict_types=1);

namespace App\Livewire\PurchaseReturn;

use App\Actions\Purchases\StorePurchaseReturnAction;
use App\Models\Supplier;
use App\Traits\LivewireCartTrait;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Traits\WithAlert;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithAlert;
    use LivewireCartTrait;

    public $warehouse_id;

    #[Validate('required')]
    public $supplier_id;

    public $reference;

    #[Validate('required|numeric|min:0|max:100')]
    public $tax_percentage;

    #[Validate('required|numeric|min:0|max:100')]
    public $discount_percentage;

    #[Validate('required|numeric')]
    public $shipping_amount;

    #[Validate('required|numeric')]
    public $total_amount;

    #[Validate('required|numeric')]
    public $paid_amount;

    #[Validate('required|string|max:255')]
    public $status;

    #[Validate('required|string|max:255')]
    public $payment_method;

    #[Validate('nullable|string|max:1000')]
    public $note;

    public $date;

    public function mount(string $cartInstance = 'purchase_return'): void
    {
        abort_if(Gate::denies('purchase_return_create'), 403);

        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        $this->tax_percentage = 0;
        $this->discount_percentage = 0;
        $this->shipping_amount = 0;
        $this->paid_amount = 0;
        $this->payment_method = 'Cash';
        $this->status = 'Pending';
        $this->date = date('Y-m-d');

        if (settings()->default_warehouse_id !== null) {
            $this->warehouse_id = settings()->default_warehouse_id;
        }
    }

    public function hydrate(): void
    {
        $this->total_amount = $this->calculateTotal();
    }

    public function calculateTotal(): float
    {
        return $this->cartTotal + (float) $this->shipping_amount;
    }

    public function proceed(): void
    {
        if ($this->supplier_id !== null) {
            $this->store();
        } else {
            $this->alert('error', __('Please select a supplier!'));
        }
    }

    public function store(): void
    {
        abort_if(Gate::denies('purchase_return_create'), 403);

        $this->validate();

        app(StorePurchaseReturnAction::class)(
            [
                'date'                => $this->date,
                'reference'           => $this->reference,
                'supplier_id'         => $this->supplier_id,
                'user_id'             => Auth::id(),
                'tax_percentage'      => $this->tax_percentage,
                'discount_percentage' => $this->discount_percentage,
                'shipping_amount'     => $this->shipping_amount,
                'paid_amount'         => $this->paid_amount,
                'total_amount'        => $this->total_amount,
                'status'              => $this->status,
                'payment_method'      => $this->payment_method,
                'note'                => $this->note,
            ],
            $this->cartContent->toArray(),
            $this->cartTax,
            $this->cartDiscount
        );

        $this->clearCart();

        $this->alert('success', __('Purchase Return created successfully.'));
        $this->redirectRoute('purchase-returns.index', navigate: true);
    }

    public function render()
    {
        $suppliers = Supplier::select(['id', 'name'])->get();

        return view('livewire.purchase-return.create', [
            'cart_items' => $this->cartContent,
            'suppliers'  => $suppliers,
        ]);
    }
}
