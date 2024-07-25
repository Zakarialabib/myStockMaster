<?php

declare(strict_types=1);

namespace App\Livewire\PurchaseReturn;

use App\Models\PurchaseReturn;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Edit extends Component
{
    use LivewireAlert;

    public $purchasereturn;

    public bool $editModal = false;

    /** @var array */
    protected $rules = [
        'supplier_id'         => 'required|numeric',
        'reference'           => 'required|string|max:255',
        'tax_percentage'      => 'required|integer|min:0|max:100',
        'discount_percentage' => 'required|integer|min:0|max:100',
        'shipping_amount'     => 'required|numeric',
        'total_amount'        => 'required|numeric',
        'paid_amount'         => 'required|numeric',
        'status'              => 'required|integer|max:255',
        'payment_method'      => 'required|integer|max:255',
        'note'                => 'nullable|string|max:1000',
    ];

    public function editModal($id): void
    {
        abort_if(Gate::denies('purchase_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->purchasereturn = PurchaseReturn::whereId($id)->firstOrFail();

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->purchasereturn->update($this->all());

        $this->editModal = false;

        $this->alert('success', 'PurchaseReturn updated successfully.');
    }

    public function render()
    {
        return view('livewire.purchase-return.edit');
    }
}
