<?php

declare(strict_types=1);

namespace App\Livewire\Warehouses;

use App\Livewire\Forms\WarehouseForm;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{
    use WithAlert;

    public bool $showModal = false;

    public WarehouseForm $form;

    public function render()
    {
        abort_if(Gate::denies('warehouse_create'), 403);

        return view('livewire.warehouses.create');
    }

    #[On('createModal')]
    public function openModal(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->showModal = true;
    }

    public function create(): void
    {
        $this->form->store();

        $this->alert('success', __('Warehouse created successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->form->reset();

        $this->showModal = false;
    }
}
