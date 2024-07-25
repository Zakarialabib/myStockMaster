<?php

declare(strict_types=1);

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $editModal = false;

    /** @var mixed */
    public $supplier;

    #[Validate('required|string|min:3|max:255', message: 'The name field is required and must be a string between 3 and 255 characters.')]
    public string $name;

    #[Validate('nullable|max:255', message: 'The email field must be a string with a maximum of 255 characters.')]
    public ?string $email = null;

    #[Validate('required|numeric', message: 'The phone field is required and must be a numeric value.')]
    public $phone;

    #[Validate('nullable|max:255', message: 'The city field must be a string with a maximum of 255 characters.')]
    public ?string $city = null;

    #[Validate('nullable|max:255', message: 'The country field must be a string with a maximum of 255 characters.')]
    public ?string $country = null;

    #[Validate('nullable|max:255', message: 'The address field must be a string with a maximum of 255 characters.')]
    public ?string $address = null;

    #[Validate('nullable|max:255', message: 'The tax number field must be a string with a maximum of 255 characters.')]
    public ?string $tax_number = null;

    public function render()
    {
        abort_if(Gate::denies('supplier update'), 403);

        return view('livewire.suppliers.edit');
    }

    #[On('editModal')]
    public function openModal($id): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->supplier = Supplier::whereId($id)->first();

        $this->name = $this->supplier->name;

        $this->email = $this->supplier->email;

        $this->phone = $this->supplier->phone;

        $this->city = $this->supplier->city;

        $this->country = $this->supplier->country;

        $this->address = $this->supplier->address;

        $this->tax_number = $this->supplier->tax_number;

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->supplier->update($this->all());

        $this->alert('success', __('Supplier updated successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->editModal = false;
    }
}
