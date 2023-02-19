<?php

declare(strict_types=1);

namespace App\Http\Livewire\Customers;

use App\Models\Customer;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class Edit extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $editModal = false;

    /** @var mixed */
    public $customer;

    /** @var array<string> */
    public $listeners = ['editModal'];

    /** @var array */
    protected $rules = [
        'customer.name'       => 'required|string|min:3|max:255',
        'customer.email'      => 'nullable|email|max:255',
        'customer.phone'      => 'required|numeric',
        'customer.city'       => 'nullable|max:255',
        'customer.country'    => 'nullable|max:255',
        'customer.address'    => 'nullable|max:255',
        'customer.tax_number' => 'nullable|max:255',
    ];

    protected $messages = [
        'customer.name.required'  => 'The name field cannot be empty.',
        'customer.phone.required' => 'The code field cannot be empty.',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.customers.edit');
    }

    public function editModal($id)
    {
        abort_if(Gate::denies('customer_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->customer = Customer::findOrFail($id);

        $this->editModal = true;
    }

    public function update(): void
    {
        $validatedData = $this->validate();

        try {
            $this->customer->save($validatedData);

            $this->alert('success', __('Customer updated successfully.'));

            $this->editModal = false;

            $this->emit('refreshIndex');
        } catch (Throwable $th) {
            $this->alert('success', __('Error.').$th->getMessage());
        }
    }
}
