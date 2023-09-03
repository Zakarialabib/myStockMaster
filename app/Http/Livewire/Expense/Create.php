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
    public $listeners = ['createModal'];

    public $createModal = false;

    /** @var mixed */
    public $expense;

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

    public function render()
    {
        abort_if(Gate::denies('expense_create'), 403);

        return view('livewire.expense.create');
    }

    public function createModal(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->expense = new Expense();

        $this->expense->date = date('Y-m-d');

        $this->createModal = true;
    }

    public function create(): void
    {
        try {
            $validatedData = $this->validate();

            $this->expense->save($validatedData);

            $this->expense->user()->associate(auth()->user());

            $this->alert('success', __('Expense created successfully.'));

            $this->emit('refreshIndex');

            $this->createModal = false;
        } catch (Throwable $th) {
            $this->alert('error', $th->getMessage());
        }
    }

    public function getExpenseCategoriesProperty()
    {
        return ExpenseCategory::select('name', 'id')->get();
    }

    public function getWarehousesProperty()
    {
        return Warehouse::select('name', 'id')->get();
    }
}
