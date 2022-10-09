<?php

namespace App\Http\Livewire\Warehouses;

use Livewire\Component;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;

class Create extends Component
{

    public $listeners = ['createWarehouse'];
    
    public $createWarehouse; 

    public array $rules = [
        'warehouse.name' => ['string', 'required'],
        'warehouse.mobile' => ['string', 'nullable'],
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

        $this->createWarehouse = false;

        $this->emit('refreshIndex');

    }
}
