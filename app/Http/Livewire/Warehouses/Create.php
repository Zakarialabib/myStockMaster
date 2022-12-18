<?php

declare(strict_types=1);

namespace App\Http\Livewire\Warehouses;

use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Create extends Component
{
    use LivewireAlert;

    /** @var string[] $listeners */
    public $listeners = ['createWarehouse'];

    /** @var bool */
    public $createWarehouse = false;

    /** @var mixed $warehouse */
    public $warehouse;

    public array $rules = [
        'warehouse.name'    => ['string', 'required'],
        'warehouse.phone'   => ['string', 'nullable'],
        'warehouse.country' => ['string', 'nullable'],
        'warehouse.city'    => ['string', 'nullable'],
        'warehouse.email'   => ['string', 'nullable'],
    ];

    public function mount(Warehouse $warehouse): void
    {
        $this->warehouse = $warehouse;
    }

    public function render(): View|Factory
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
