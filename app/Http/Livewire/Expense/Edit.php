<?php

declare(strict_types=1);

namespace App\Http\Livewire\Expense;

use App\Models\Warehouse;
use Livewire\Component;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Edit extends Component
{
    use LivewireAlert;

    public $listeners = [
        'editModal',
    ];

    /** @var bool */
    public $editModal = false;

    /** @var mixed */
    public $expense;

    /** @var array */
    protected $rules = [
        'expense.reference'    => 'required|string|max:255',
        'expense.category_id'  => 'required|integer|exists:expense_categories,id',
        'expense.date'         => 'required|date',
        'expense.amount'       => 'required|numeric',
        'expense.details'      => 'nullable|string|max:255',
        'expense.warehouse_id' => 'nullable',
    ];

    protected $messages = [
        'expense.name.required'        => 'The name field cannot be empty.',
        'expense.category_id.required' => 'The category field cannot be empty.',
        'expense.date.required'        => 'The date field cannot be empty.',
        'expense.amount.required'      => 'The amount field cannot be empty.',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function getExpenseCategoriesProperty()
    {
        return ExpenseCategory::select('name', 'id')->get();
    }

    public function getWarehousesProperty()
    {
        return Warehouse::select('name', 'id')->get();
    }

    public function render()
    {
        return view('livewire.expense.edit');
    }

    public function editModal(Expense $expense): void
    {
        abort_if(Gate::denies('expense_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->expense = Expense::find($expense->id);

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->expense->save();

        $this->alert('success', __('Expense updated successfully.'));

        $this->emit('refreshIndex');

        $this->editModal = false;
    }
}
