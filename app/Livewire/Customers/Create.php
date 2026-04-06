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

class Create extends Component
{
    use WithAlert;

    public $showModal = false;

    public Customer $customer;

    public CustomerForm $form;

    #[On('showModal')]
    public function openCreateModal(): void
    {
        $this->resetErrorBag();
        $this->form->reset();
        $this->showModal = true;
    }

    public function create(CustomerService $customerService): void
    {
        $this->validate();

        $customerService->create($this->form->all());

        $this->alert('success', __('Customer created successfully'));

        $this->dispatch('refreshIndex');

        $this->showModal = false;
    }

    #[Computed]
    public function customerGroups()
    {
        return CustomerGroup::pluck('name', 'id')->toArray();
    }

    #[Computed]
    public function roles()
    {
        return Role::pluck('name', 'id')->toArray();
    }

    public function render()
    {
        abort_if(Gate::denies('customer_create'), 403);

        return view('livewire.customers.create');
    }
}
