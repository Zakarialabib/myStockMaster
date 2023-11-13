<?php

declare(strict_types=1);

namespace App\Http\Livewire\CustomerGroup;

use App\Models\CustomerGroup;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public $listeners = ['createModal'];

    public $createModal = false;

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
        // abort_if(Gate::denies('expense_category_create'), 403);

        return view('livewire.customer-group.create');
    }

    public function createModal(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->customergroup = new CustomerGroup();

        $this->createModal = true;
    }

    public function create(): void
    {
        $validatedData = $this->validate();

        $this->customergroup->save($validatedData);

        $this->alert('success', __('Customer group created successfully.'));

        $this->emit('refreshIndex');

        $this->createModal = false;
    }
}
