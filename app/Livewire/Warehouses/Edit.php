<?php

declare(strict_types=1);

namespace App\Livewire\Warehouses;

use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class Edit extends Component
{
    use LivewireAlert;

    /** @var bool */
    public $editModal = false;

    public $warehouse;

    #[Validate('string|required|max:255')]
    public $name;

    #[Validate('numeric|nullable|max:255')]
    public $phone;

    #[Validate('nullable|max:255')]
    public $country;

    #[Validate('nullable|max:255')]
    public $city;

    #[Validate('nullable|max:255')]
    public $email;

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

        $this->warehouse = Warehouse::find($id);

        $this->name = $this->warehouse->name;

        $this->phone = $this->warehouse->phone;

        $this->country = $this->warehouse->country;

        $this->city = $this->warehouse->city;

        $this->email = $this->warehouse->email;

        $this->editModal = true;
    }

    public function update(): void
    {
        abort_if(Gate::denies('warehouse_update'), 403);

        $this->validate();

        $this->warehouse->save();

        $this->editModal = false;

        $this->alert('success', __('Warehouse updated successfully'));
    }
}
