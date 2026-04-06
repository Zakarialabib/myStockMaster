<?php

declare(strict_types=1);

namespace App\Livewire\Customers;

use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Role;
use App\Services\CustomerService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithAlert;
    use WithFileUploads;

    public $showModal = false;

    /** @var mixed */
    public $customer;

    public CustomerForm $form;

    public function render()
    {
        return view('livewire.customers.edit');
    }

    #[On('showModal')]
    public function openEditModal($id): void
    {
        abort_if(Gate::denies('customer_update'), 403);

        $this->resetErrorBag();
        $this->form->reset();

        $this->customer = Customer::findOrFail($id);

        $this->form->name = $this->customer->name;
        $this->form->email = $this->customer->email;
        $this->form->phone = $this->customer->phone;
        $this->form->city = $this->customer->city;
        $this->form->country = $this->customer->country;
        $this->form->address = $this->customer->address;
        $this->form->customer_group_id = $this->customer->customer_group_id;
        $this->form->tax_number = $this->customer->tax_number;

        $this->showModal = true;
    }

    #[Computed]
    public function roles()
    {
        return Role::pluck('name', 'id')->toArray();
    }

    #[Computed]
    public function customerGroups()
    {
        return CustomerGroup::pluck('name', 'id')->toArray();
    }

    public function update(CustomerService $customerService): void
    {
        $this->validate();

        $customerService->update($this->customer, $this->form->all());

        $this->alert('success', __('Customer updated successfully.'));

        $this->showModal = false;

        $this->dispatch('refreshIndex')->to(Index::class);
    }
}
