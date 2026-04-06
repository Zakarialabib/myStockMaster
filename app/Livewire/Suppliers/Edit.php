<?php

declare(strict_types=1);

namespace App\Livewire\Suppliers;

use App\Livewire\Forms\SupplierForm;
use App\Models\Supplier;
use App\Services\SupplierService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithAlert;
    use WithFileUploads;

    public bool $showModal = false;

    /** @var mixed */
    public Supplier $supplier;

    public SupplierForm $form;

    public function render()
    {
        abort_if(Gate::denies('supplier update'), 403);

        return view('livewire.suppliers.edit');
    }

    #[On('showModal')]
    public function openModal($id): void
    {
        $this->resetErrorBag();
        $this->form->reset();

        $this->supplier = Supplier::whereId($id)->first();

        $this->form->name = $this->supplier->name;
        $this->form->email = $this->supplier->email;
        $this->form->phone = $this->supplier->phone;
        $this->form->city = $this->supplier->city;
        $this->form->country = $this->supplier->country;
        $this->form->address = $this->supplier->address;
        $this->form->tax_number = $this->supplier->tax_number;

        $this->showModal = true;
    }

    public function update(SupplierService $supplierService): void
    {
        $this->validate();

        $supplierService->update($this->supplier, $this->form->all());

        $this->alert('success', __('Supplier updated successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->showModal = false;
    }
}
