<?php

declare(strict_types=1);

namespace App\Http\Livewire\Customers;

use App\Models\Customer;
use App\Models\Wallet;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = ['createCustomer'];

    public $createCustomer = false;

    /** @var mixed */
    public $customer;

    protected $rules = [
        'customer.name'       => 'required|string|min:3|max:255',
        'customer.email'      => 'nullable|email|max:255',
        'customer.phone'      => 'required|numeric',
        'customer.city'       => 'nullable|min:3|max:255',
        'customer.country'    => 'nullable|min:3|max:255',
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

    public function createCustomer(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->customer = new Customer();

        $this->createCustomer = true;
    }

    public function create(): void
    {
        try {
            $validatedData = $this->validate();

            $this->customer->save($validatedData);

            if ($this->customer) {
                Wallet::create([
                    'customer_id' => $this->customer->id,
                    'balance'     => 0,
                ]);
            }
            $this->alert('success', __('Customer created successfully'));

            $this->emit('refreshIndex');

            $this->createCustomer = false;
        } catch (Throwable $th) {
            $this->alert('error', __('Error.').$th->getMessage());
        }
    }

    public function render()
    {
        abort_if(Gate::denies('customer_create'), 403);

        return view('livewire.customers.create');
    }
}
