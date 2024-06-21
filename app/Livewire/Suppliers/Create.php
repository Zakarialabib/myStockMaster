<?php

declare(strict_types=1);

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
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

    public Supplier $supplier;

    #[Validate('required|string|min:3|max:255', message: 'The name field is required and must be a string between 3 and 255 characters.')]
    public $name;

    #[Validate('required|numeric', message: 'The phone field is required and must be a numeric value.')]
    public $phone;

    #[Validate('nullable|email|max:255', message: 'The email field must be a valid email address with a maximum of 255 characters.')]
    public $email;

    public $address;

    public $city;

    public $country;

    public $tax_number;

    public function render()
    {
        abort_if(Gate::denies('supplier_create'), 403);

        return view('livewire.suppliers.create');
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

        Supplier::create($this->all());

        $this->alert('success', __('Supplier created successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->reset(['name', 'email', 'phone', 'address', 'city', 'country', 'tax_number']);

        $this->createModal = false;
    }
}
