<?php

declare(strict_types=1);

namespace App\Livewire\Warehouses;

use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    /** @var bool */
    public $createModal = false;

    public Warehouse $warehouse;

    #[Validate('required')]
    #[Validate('max:255')]
    public $name;

    #[Validate('numeric')]
    public $phone;

    #[Validate('nullable')]
    #[Validate('max:255')]
    public $country;

    #[Validate('nullable')]
    #[Validate('max:255')]
    public $city;

    #[Validate('email')]
    #[Validate('max:255')]
    public $email;

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

        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        Warehouse::create($this->all());

        $this->alert('success', __('Warehouse created successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->reset(['name', 'phone', 'country', 'city', 'email']);

        $this->createModal = false;
    }
}
