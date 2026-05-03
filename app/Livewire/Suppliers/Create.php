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

class Create extends Component
{
    use WithAlert;

    public bool $showModal = false;

    public ?Supplier $supplier = null;

    public SupplierForm $form;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('supplier_create'), 403);

        return view('livewire.suppliers.create');
    }

    #[On('showModal')]
    public function openModal(): void
    {
        $this->resetErrorBag();
        $this->form->reset();
        $this->showModal = true;
    }

    public function create(SupplierService $supplierService): void
    {
        $this->validate();

        $supplierService->create($this->form->all());

        $this->alert('success', __('Supplier created successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->showModal = false;
    }
}
