<?php

declare(strict_types=1);

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    use WithAlert;

    public bool $showModal = false;

    public mixed $customer;

    #[On('showModal')]
    public function openModal(int|string $id): void
    {
        abort_if(Gate::denies('customer_access'), 403);

        $this->customer = Customer::query()->find($id);

        $this->showModal = true;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.customers.show');
    }
}
