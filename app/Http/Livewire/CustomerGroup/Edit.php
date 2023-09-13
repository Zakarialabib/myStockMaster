<?php

declare(strict_types=1);

namespace App\Http\Livewire\CustomerGroup;

use App\Models\CustomerGroup;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Throwable;

class Edit extends Component
{
    use LivewireAlert;

    public $listeners = [
        'editModal',
    ];

    /** @var bool */
    public $editModal = false;

    /** @var mixed */
    public $customergroup;

    /** @var array */
    protected $rules = [
        'customergroup.name'       => 'required|min:3|max:255',
        'customergroup.percentage' => 'required',
    ];

    protected $messages = [
        'customergroup.name.required'       => 'The name field cannot be empty.',
        'customergroup.percentage.required' => 'The percentage field cannot be empty.',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.customer-group.edit');
    }

    public function editModal($id): void
    {
        // abort_if(Gate::denies('expense_category_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->customergroup = CustomerGroup::where('id', $id)->firstOrFail();

        $this->editModal = true;
    }

    public function update(): void
    {
        try {
            $validatedData = $this->validate();

            $this->customergroup->save($validatedData);

            $this->alert('success', __('Customer group Updated Successfully.'));

            $this->emit('refreshIndex');

            $this->editModal = false;
        } catch (Throwable $th) {
            $this->alert('error', $th->getMessage());
        }
    }
}
