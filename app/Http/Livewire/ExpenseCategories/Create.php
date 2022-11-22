<?php

namespace App\Http\Livewire\ExpenseCategories;

use Livewire\Component;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Create extends Component
{
    use LivewireAlert;

    public $listeners = ['createExpenseCategory'];

    public $createExpenseCategory;

    public array $rules = [
        'expenseCategory.name' => 'required',
        'expenseCategory.description' => '',
    ];

    public function mount(ExpenseCategory $expenseCategory)
    {
        $this->expenseCategory = $expenseCategory;
    }

    public function render()
    {
        abort_if(Gate::denies('expense_category_create'), 403);

        return view('livewire.expense-categories.create');
    }

    public function createExpenseCategory()
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createExpenseCategory = true;
    }

    public function create()
    {
        $this->validate();

        $this->expenseCategory->save();

        $this->alert('success', __('Expense created successfully.'));

        $this->emit('refreshIndex');

        $this->createExpenseCategory = false;
    }
}
