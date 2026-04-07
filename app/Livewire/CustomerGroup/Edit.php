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

class Edit extends Component
{
    use WithAlert;

    /** @var bool */
    public $editModal = false;

    /** @var mixed */
    public $customergroup;

    public CustomerGroupForm $form;

    /** @var array */
    public function render()
    {
        abort_if(Gate::denies('customer-group_update'), 403);

        return view('livewire.customer-group.edit');
    }

    #[On('editModal')]
    public function openModal($id): void
    {
        $this->resetErrorBag();
        $this->form->reset();

        $this->customergroup = CustomerGroup::where('id', $id)->firstOrFail();

        $this->form->name = $this->customergroup->name;
        $this->form->percentage = $this->customergroup->percentage;

        $this->editModal = true;
    }

    public function update(CustomerGroupService $customerGroupService): void
    {
        $this->validate();

        $customerGroupService->update(
            $this->customergroup,
            $this->form->all()
        );

        $this->alert('success', __('Customer group Updated Successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->editModal = false;
    }
}
