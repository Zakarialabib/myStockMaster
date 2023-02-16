<?php

declare(strict_types=1);

namespace App\Http\Livewire\Expense;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    /** @var string[] */
    public $listeners = ['createExpense'];

    public $createExpense = false;

    /** @var mixed */
    public $expense;

    public $reference;

    public $category_id;

    public $date;

    public $amount;

    public $details;

    public $user_id;

    public $warehouse_id;

    public $listsForFields = [];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected $rules = [
        'reference'    => 'required|string|max:255',
        'category_id'  => 'required|integer|exists:expense_categories,id',
        'date'         => 'required|date',
        'amount'       => 'required|numeric',
        'details'      => 'nullable|string|min:3',
        'user_id'      => 'nullable',
        'warehouse_id' => 'nullable',
    ];

    public function mount(): void
    {
        $this->date = date('Y-m-d');
        $this->initListsForFields();
    }

    public function render()
    {
        abort_if(Gate::denies('expense_create'), 403);

        return view('livewire.expense.create');
    }

    public function createExpense(): void
    {
        $this->reset();

        $this->createExpense = true;

        $this->initListsForFields();
    }

    public function create(): void
    {
        $validatedData = $this->validate();

        $expense = Expense::create($validatedData);

        $expense->user()->associate(auth()->user());

        $this->alert('success', __('Expense created successfully.'));

        $this->emit('refreshIndex');

        $this->createExpense = false;
    }

    protected function initListsForFields()
    {
        $this->listsForFields['expensecategories'] = ExpenseCategory::pluck('name', 'id')->toArray();
        $this->listsForFields['warehouses'] = Warehouse::pluck('name', 'id')->toArray();
    }
}
