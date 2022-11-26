<?php

namespace App\Http\Livewire\Warehouses;

use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Create extends Component
{
    public $listeners = ['createWarehouse'];

    /** @var boolean */
    public $createWarehouse = false;

    public array $rules = [
        'warehouse.name' => ['string', 'required'],
        'warehouse.phone' => ['string', 'nullable'],
        'warehouse.country' => ['string', 'nullable'],
        'warehouse.city' => ['string', 'nullable'],
        'warehouse.email' => ['string', 'nullable'],
    ];

    public function mount(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }

    public function render()
    {
        return view('livewire.warehouses.create');
    }

    public function createWarehouse()
    {
        abort_if(Gate::denies('warehouse_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->createWarehouse = true;
    }

    public function create()
    {
        abort_if(Gate::denies('warehouse_create'), 403);

        $this->validate();

        $this->warehouse->save();

        $this->alert('success', __('Warehouse created successfully.'));
        
        $this->emit('refreshIndex');

        $this->createWarehouse = false;

    }
}
