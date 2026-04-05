<?php

declare(strict_types=1);

namespace App\Livewire\Warehouses;

use App\Livewire\Forms\WarehouseForm;
use App\Models\Warehouse;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Edit extends Component
{
    use WithAlert;

    public bool $showModal = false;

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

        $this->resetValidation();

        $warehouse = Warehouse::find($id);

        $this->form->setWarehouse($warehouse);

        $this->showModal = true;
    }

    public function update(): void
    {
        abort_if(Gate::denies('warehouse_update'), 403);

        $this->form->update();

        $this->showModal = false;

        $this->alert('success', __('Warehouse updated successfully'));
    }
}
