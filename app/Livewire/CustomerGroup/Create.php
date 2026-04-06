<?php

declare(strict_types=1);

namespace App\Livewire\CustomerGroup;

use App\Livewire\Forms\CustomerGroupForm;
use App\Models\CustomerGroup;
use App\Services\CustomerGroupService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{
    use WithAlert;

    public $createModal = false;

    public CustomerGroup $customergroup;

    public CustomerGroupForm $form;

    public function render()
    {
        abort_if(Gate::denies('customer-group_create'), 403);

        return view('livewire.customer-group.create');
    }

    #[On('createModal')]
    public function openCreateModal(): void
    {
        $this->resetErrorBag();
        $this->form->reset();

        $this->createModal = true;
    }

    public function create(CustomerGroupService $customerGroupService): void
    {
        $this->validate();

        $customerGroupService->create($this->form->all());

        $this->alert('success', __('Customer group created successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->createModal = false;
    }
}
