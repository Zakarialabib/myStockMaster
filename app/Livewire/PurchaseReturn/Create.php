<?php

declare(strict_types=1);

namespace App\Livewire\PurchaseReturn;

use App\Models\PurchaseReturn;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Attributes\Validate;

class Create extends Component
{
    use LivewireAlert;

    public $warehouse_id;

    #[Validate('required')]
    public $supplier_id;

    #[Validate('required|string|max:255')]
    public $reference;

    #[Validate('required|integer|min:0|max:100')]
    public $tax_percentage;

    #[Validate('required|integer|min:0|max:100')]
    public $discount_percentage;

    #[Validate('required|numeric')]
    public $shipping_amount;

    #[Validate('required|numeric')]
    public $total_amount;

    #[Validate('required|numeric')]
    public $paid_amount;

    #[Validate('required|integer|max:255')]
    public $status;

    #[Validate('required|integer|max:255')]
    public $payment_method;

    #[Validate('nullable|string|max:1000')]
    public $note;

    public function mount(): void
    {
        abort_if(Gate::denies('purchase return_create'), 403);

        if (settings()->default_warehouse_id !== null) {
            $this->warehouse_id = settings()->default_warehouse_id;
        }

        Cart::instance('purchase_return')->destroy();
    }

    public function create(): void
    {
        abort_if(Gate::denies('purchase_create'), 403);

        $this->validate();

        PurchaseReturn::create($this->all());

        $this->alert('success', 'PurchaseReturn created successfully.');
    }

    public function render()
    {
        return view('livewire.purchase-return.create');
    }
}
