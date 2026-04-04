<?php

declare(strict_types=1);

namespace App\Livewire\Warehouses;

use App\Models\Warehouse;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    use WithAlert;

    public bool $showModal = false;

    public Warehouse $warehouse;

    #[Validate('required')]
    #[Validate('max:255')]
    public string $name;

    #[Validate('numeric')]
    public ?string $phone = null;

    #[Validate('nullable')]
    #[Validate('max:255')]
    public ?string $country = null;

    #[Validate('nullable')]
    #[Validate('max:255')]
    public ?string $city = null;

    #[Validate('email')]
    #[Validate('max:255')]
    public ?string $email = null;

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
        $this->validate();

        Warehouse::create($this->all());

        $this->alert('success', __('Warehouse created successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->reset(['name', 'phone', 'country', 'city', 'email']);

        $this->showModal = false;
    }
}
