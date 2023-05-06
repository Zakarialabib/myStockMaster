<?php

declare(strict_types=1);

namespace App\Http\Livewire\Expense;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = ['createExpense'];

    public $createExpense = false;

    /** @var mixed */
    public $expense;

    public $listsForFields = [];

    protected $rules = [
        'expense.reference'    => 'required|string|max:255',
        'expense.category_id'  => 'required|integer|exists:expense_categories,id',
        'expense.date'         => 'required|date',
        'expense.amount'       => 'required|numeric',
        'expense.details'      => 'nullable|string|min:3',
        'expense.user_id'      => 'nullable',
        'expense.warehouse_id' => 'nullable',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount(): void
    {
        $this->initListsForFields();
    }

    public function render()
    {
        abort_if(Gate::denies('expense_create'), 403);

        return view('livewire.expense.create');
    }

    public function createExpense(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->expense = new Expense();

        $this->createExpense = true;
    }

    public function create(): void
    {
        try {
            $validatedData = $this->validate();

            $this->expense->save($validatedData);

            $this->expense->user()->associate(auth()->user());

            $this->alert('success', __('Expense created successfully.'));

            $this->emit('refreshIndex');

            $this->createExpense = false;
        } catch (Throwable $th) {
            $this->alert('error', $th->getMessage());
        }
    }

    protected function initListsForFields()
    {
        $this->listsForFields['expensecategories'] = ExpenseCategory::select('name', 'id')->get();
        $this->listsForFields['warehouses'] = Warehouse::select('name', 'id')->get();
    }
}
