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

    /** @var string[] */
    public $listeners = ['createExpenseCategory'];

    public $createExpenseCategory = false;

    /** @var mixed */
    public $expenseCategory;

    /** @var array */
    protected $rules = [
        'expenseCategory.name'        => 'required',
        'expenseCategory.description' => 'nullable',
    ];

    public function mount(ExpenseCategory $expenseCategory): void
    {
        $this->expenseCategory = $expenseCategory;
    }

    public function render()
    {
        abort_if(Gate::denies('expense_category_create'), 403);

        return view('livewire.expense-categories.create');
    }

    public function createExpenseCategory(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createExpenseCategory = true;
    }

    public function create(): void
    {
        $this->validate();

        $this->expenseCategory->save();

        $this->alert('success', __('Expense created successfully.'));

        $this->emit('refreshIndex');

        $this->createExpenseCategory = false;
    }
}
