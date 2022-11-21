<?php

namespace App\Http\Livewire\Customers;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Wallet;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Create extends Component
{
    use LivewireAlert;

    public $listeners = ['createCustomer'];
    
    public $createCustomer; 
    
    public $name, $email ,$phone, $city, $country ,$address, $tax_number;

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|max:255',
        'phone' => 'required|numeric',
        'city' => 'nullable',
        'country' => 'nullable',
        'address' => 'nullable',
        'tax_number' => 'nullable',
    ];

    public function render()
    {
        abort_if(Gate::denies('customer_create'), 403);
        
        return view('livewire.customers.create');
    }

    public function createCustomer()
    {
        $this->reset();
        
        $this->createCustomer = true;
    }

    public function create()
    {
        $validatedData = $this->validate();

        Customer::create($validatedData);

        if($this->customer) {
            $wallet = Wallet::create([
                'customer_id' => $this->customer->id,
                'balance' => 0,
            ]);
            $this->alert('success', __('Customer created successfully'));
        }
        else {
            $this->alert('error', __('Customer not created'));
        }

        $this->emit('refreshIndex');

        $this->createCustomer = false;
        
    }
}
