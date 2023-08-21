<?php

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

    public $editModal = false;
    public $expense;
    public $listeners = [
        'editModal'
    ];
    protected $rules = [
        'expense.reference'    => 'required|string|max:255',
        'expense.category_id'  => 'required|integer|exists:expense_categories,id',
        'expense.date'         => 'required|date',
        'expense.amount'       => 'required|numeric',
        'expense.details'      => 'nullable|string|max:255',
        'expense.warehouse_id' => 'nullable',
    ];

    public function editModal($id): void
    {

        $this->resetErrorBag();

        $this->resetValidation();

        $this->expense = Expense::find($id);

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
        abort_if(Gate::denies('expense_update'), 403);

        return view('livewire.expense.edit');
    }
}
