<?php

declare(strict_types=1);

namespace App\Livewire\Warehouses;

use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use App\Traits\WithAlert;

class Edit extends Component
{
    use WithAlert;

    public bool $editModal = false;

    public Warehouse $warehouse;

    #[Validate('string|required|max:255')]
    public string $name;

    #[Validate('numeric|nullable|max:255')]
    public ?string $phone = null;

    #[Validate('nullable|max:255')]
    public ?string $country = null;

    #[Validate('nullable|max:255')]
    public ?string $city = null;

    #[Validate('nullable|max:255')]
    public ?string $email = null;

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
