<?php

declare(strict_types=1);

namespace App\Livewire\CustomerGroup;

use App\Models\CustomerGroup;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    use LivewireAlert;

    /** @var bool */
    public $editModal = false;

    /** @var mixed */
    public $customergroup;

    #[Validate('required', message: 'The name field cannot be empty.')]
    #[Validate('min:3', message: 'The name must be at least 3 characters.')]
    #[Validate('max:255', message: 'The name may not be greater than 255 characters.')]
    public $name;

    #[Validate('required', message: 'The percentage field cannot be empty.')]
    public $percentage;

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

        $this->resetValidation();

        $this->customergroup = CustomerGroup::where('id', $id)->firstOrFail();

        $this->name = $this->customergroup->name;

        $this->percentage = $this->customergroup->percentage;

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->customergroup->update(
            $this->all(),
        );

        $this->alert('success', __('Customer group Updated Successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->editModal = false;
    }
}
