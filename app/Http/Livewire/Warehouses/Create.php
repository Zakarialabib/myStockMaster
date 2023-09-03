<?php

declare(strict_types=1);

namespace App\Http\Livewire\Warehouses;

use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = ['createModal'];

    /** @var bool */
    public $createModal = false;

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
        abort_if(Gate::denies('warehouse_create'), 403);

        return view('livewire.warehouses.create');
    }

    public function createModal()
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        $this->warehouse->save();

        $this->alert('success', __('Warehouse created successfully.'));

        $this->emit('refreshIndex');

        $this->reset('warehouse');

        $this->createModal = false;
    }
}
