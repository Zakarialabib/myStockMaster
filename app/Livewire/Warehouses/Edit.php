<?php

declare(strict_types=1);

namespace App\Livewire\Warehouses;

use App\Livewire\Forms\WarehouseForm;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Edit extends Component
{
    use WithAlert;

    public bool $editModal = false;

    public Warehouse $warehouse;

    public WarehouseForm $form;

    public function render()
    {
        return view('livewire.warehouses.edit');
    }

    #[On('editModal')]
    public function openModal($id): void
    {
        abort_if(Gate::denies('warehouse_update'), 403);

        $this->resetErrorBag();

        $this->form->reset();

        $this->warehouse = Warehouse::findOrFail($id);

        $this->form->name = $this->warehouse->name;
        $this->form->phone = $this->warehouse->phone;
        $this->form->country = $this->warehouse->country;
        $this->form->city = $this->warehouse->city;
        $this->form->email = $this->warehouse->email;

        $this->editModal = true;
    }

    public function update(WarehouseService $service): void
    {
        abort_if(Gate::denies('warehouse_update'), 403);

        $this->form->validate();

        $service->update($this->warehouse, $this->form->all());

        $this->editModal = false;

        $this->alert('success', __('Warehouse updated successfully'));
    }
}
