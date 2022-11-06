<?php

namespace App\Http\Livewire\Expense;

use Livewire\Component;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Create extends Component
{
    use LivewireAlert;

    public $listeners = ['createExpense'];
    
    public $createExpense; 
    
    public array $listsForFields = [];
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    
    public array $rules = [
        'expense.reference' => 'required|string|max:255',
        'expense.category_id' => 'required|integer|exists:expense_categories,id',
        'expense.date' => 'required',
        'expense.amount' => 'required|numeric',
        'expense.details' => 'nullable|string|max:255',
        'expense.user_id' => 'nullable',
        'expense.warehouse_id' => 'nullable',
    ];

    public function mount(Expense $expense)
    {
        $this->expense = $expense;
        
        $this->initListsForFields();
    }

    public function render()
    {
        abort_if(Gate::denies('expense_create'), 403);

        return view('livewire.expense.create');
    }

    public function createExpense()
    {
        $this->resetErrorBag();

        $this->resetValidation();

        
        
        $this->createExpense = true;
    }

    public function create()
    {
        $this->validate();
        
        $user_id = auth()->user()->id;
        
        $this->expense->user_id = $user_id;

        $this->expense->save();

        $this->alert('success', __('Expense created successfully.'));

        $this->emit('refreshIndex');

        $this->createExpense = false;
        
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['expensecategories'] = ExpenseCategory::pluck('name', 'id')->toArray();
        $this->listsForFields['warehouses'] = Warehouse::pluck('name', 'id')->toArray();
    }
}
