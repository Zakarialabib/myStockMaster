<?php

declare(strict_types=1);

namespace App\Http\Livewire\Warehouses;

use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Create extends Component
{
    use LivewireAlert;

    /** @var string[] */
    public $listeners = ['createWarehouse'];

    /** @var bool */
    public $createWarehouse = false;

    /** @var mixed */
    public $warehouse;

    /** @var array */
    protected $rules = [
        'warehouse.name'    => 'string|required|max:255',
        'warehouse.phone'   => 'numeric|nullable|max:255',
        'warehouse.country' => 'nullable|max:255',
        'warehouse.city'    => 'nullable|max:255',
        'warehouse.email'   => 'nullable|max:255',
    ];

    public function mount(Warehouse $warehouse): void
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

    public function create(): void
    {
        abort_if(Gate::denies('warehouse_create'), 403);

        $this->validate();

        $this->warehouse->save();

        $this->alert('success', __('Warehouse created successfully.'));

        $this->emit('refreshIndex');

        $this->createWarehouse = false;
    }
}
