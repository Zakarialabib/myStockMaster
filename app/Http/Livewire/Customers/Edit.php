<?php

declare(strict_types=1);

namespace App\Http\Livewire\Customers;

use App\Models\Customer;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $editModal = false;

    /** @var mixed */
    public $customer;

    /** @var string[] */
    public $listeners = ['editModal'];

    /** @var array */
    protected $rules = [
        'customer.name'       => 'required|string|max:255',
        'customer.email'      => 'nullable|email|max:255',
        'customer.phone'      => 'required|numeric',
        'customer.city'       => 'nullable|max:255',
        'customer.country'    => 'nullable|max:255',
        'customer.address'    => 'nullable|max:255',
        'customer.tax_number' => 'nullable|max:255',
    ];

    public function render()
    {
        return view('livewire.customers.edit');
    }

    public function editModal($id)
    {
        abort_if(Gate::denies('customer_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->customer = Customer::where('id', $id)->firstOrFail();

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->customer->save();

        $this->editModal = false;

        $this->alert('success', __('Customer updated successfully.'));
    }
}
