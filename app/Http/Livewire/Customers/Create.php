<?php

namespace App\Http\Livewire\Customers;

use Livewire\Component;
use App\Models\Customer;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Create extends Component
{
    use LivewireAlert;

    public $listeners = ['createCustomer'];
    
    public $createCustomer; 

    public array $rules = [
        'customer.name' => 'required|string|max:255',
        'customer.email' => 'nullable|max:255',
        'customer.phone' => 'required|numeric',
        'customer.city' => 'nullable',
        'customer.country' => 'nullable',
        'customer.address' => 'nullable',
        'customer.tax_number' => 'nullable',
    ];

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function render()
    {
        abort_if(Gate::denies('customer_create'), 403);
        
        return view('livewire.customers.create');
    }

    public function createCustomer()
    {
        $this->resetErrorBag();

        $this->resetValidation();
        
        $this->createCustomer = true;
    }

    public function create()
    {
        $this->validate();

        $this->customer->save();

        $this->alert('success', __('Customer created successfully.'));

        $this->emit('refreshIndex');

        $this->createCustomer = false;
        
    }
}
