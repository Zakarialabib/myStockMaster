<?php

declare(strict_types=1);

namespace App\Http\Livewire\ExpenseCategories;

use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public $listeners = ['createModal'];

    public $createModal = false;

    /** @var mixed */
    public $expenseCategory;

    /** @var array */
    protected $rules = [
        'expenseCategory.name'        => 'required|min:3|max:255',
        'expenseCategory.description' => 'nullable',
    ];

    protected $messages = [
        'expenseCategory.name.required' => 'The name field cannot be empty.',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        abort_if(Gate::denies('expense_categories_create'), 403);

        return view('livewire.expense-categories.create');
    }

    public function createModal(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->expenseCategory = new ExpenseCategory();

        $this->createModal = true;
    }

    public function create(): void
    {
        $validatedData = $this->validate();

        $this->expenseCategory->save($validatedData);

        $this->alert('success', __('Expense created successfully.'));

        $this->emit('refreshIndex');

        $this->createModal = false;
    }
}
