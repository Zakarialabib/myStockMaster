<?php

declare(strict_types=1);

namespace App\Livewire\PurchaseReturn;

use App\Models\PurchaseReturn;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Traits\WithAlert;

class Edit extends Component
{
    use WithAlert;
    public $purchasereturn;

    #[Validate('required|numeric')]
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

    public bool $editModal = false;

    public function editModal($id): void
    {
        abort_if(Gate::denies('purchase_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->purchasereturn = PurchaseReturn::whereId($id)->firstOrFail();

        $this->supplier_id = $this->purchasereturn->supplier_id;
        $this->reference = $this->purchasereturn->reference;
        $this->tax_percentage = $this->purchasereturn->tax_percentage;
        $this->discount_percentage = $this->purchasereturn->discount_percentage;
        $this->shipping_amount = $this->purchasereturn->shipping_amount;
        $this->total_amount = $this->purchasereturn->total_amount;
        $this->paid_amount = $this->purchasereturn->paid_amount;
        $this->status = $this->purchasereturn->status;
        $this->payment_method = $this->purchasereturn->payment_method;
        $this->note = $this->purchasereturn->note;

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->purchasereturn->update([
             'supplier_id' => $this->supplier_id,
             'reference' => $this->reference,
             'tax_percentage' => $this->tax_percentage,
             'discount_percentage' => $this->discount_percentage,
             'shipping_amount' => $this->shipping_amount,
             'total_amount' => $this->total_amount,
             'paid_amount' => $this->paid_amount,
             'status' => $this->status,
             'payment_method' => $this->payment_method,
             'note' => $this->note,
        ]);

        $this->editModal = false;

        $this->alert('success', 'PurchaseReturn updated successfully.');
    }

    public function render()
    {
        return view('livewire.purchase-return.edit');
    }
}
