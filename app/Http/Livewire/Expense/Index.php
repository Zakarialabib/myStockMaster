<?php

namespace App\Http\Livewire\Expense;

use Livewire\Component;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Exports\ExpenseExport;
use App\Support\HasAdvancedFilter;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use WithPagination, WithSorting, LivewireAlert, HasAdvancedFilter;

    public $expense;

    public int $perPage;

    public int $selectPage;
    
    public $listeners = ['show','confirmDelete', 'delete', 'export','createModal', 'showModal', 'editModal'];

    public $show;
    
    public $showModal;

    public $createModal;

    public $editModal;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public bool $showFilters = false;

    public array $listsForFields = [];

    public $export;

    public array $paginationOptions;

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
    ];

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetSelected()
    {
        $this->selected = [];
    }

    public array $rules = [
        'expense.reference' => 'required|string|max:255',
        'expense.category_id' => 'required|integer|exists:expense_categories,id',
        'expense.date' => 'required|date',
        'expense.amount' => 'required|numeric',
        'expense.details' => 'nullable|string|max:255',
        'expense.user_id' => '',
        'expense.warehouse_id' => '',
    ];

    public function mount()
    {
        $this->selectPage = false;
        $this->sortField = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Expense())->orderable;
        $this->initListsForFields();
    }

    public function render()
    {
        abort_if(Gate::denies('expense_access'), 403);

        $query = Expense::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $expenses = $query->paginate($this->perPage);

        return view('livewire.expense.index', compact('expenses'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('expense_delete'), 403);

        Expense::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Expense $expense)
    {
        abort_if(Gate::denies('expense_delete'), 403);

        $expense->delete();
    }

    public function createModal()
    {
        abort_if(Gate::denies('expense_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->createModal = true;
    }

    public function create()
    {
        $this->validate();

        Expense::create($this->expense);

        $this->alert('success', 'Expense created successfully.');

        $this->createModal = false;
    }


    public function showModal(Expense $expense)
    {
        abort_if(Gate::denies('customer_show'), 403);

        $this->expense = $expense;
    }

    public function editModal(Expense $expense)
    {
        abort_if(Gate::denies('expense_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->expense = $expense;

        $this->editModal = true;
    }

    public function update()
    {
        $this->validate();

        $this->expense->save();

        $this->alert('success', 'Expense updated successfully.');

        $this->editModal = false;
    }


    public function downloadSelected()
    {
        abort_if(Gate::denies('expense_download'), 403);

        $expenses = Expense::whereIn('id', $this->selected)->get();

        return (new ExpenseExport($expenses))->download('expenses.xlsx');

    }

    public function downloadAll(Expense $expense)
    {
        abort_if(Gate::denies('expense_download'), 403);

        return (new ExpenseExport([$expense]))->download('expenses.xlsx');
    }

    public function exportSelected()
    {
        abort_if(Gate::denies('expense_download'), 403);

        $expenses = Expense::whereIn('id', $this->selected)->get();

        return (new ExpenseExport($expenses))->download('expenses.pdf');

    }

    public function exportAll(Expense $expense)
    {
        abort_if(Gate::denies('expense_download'), 403);

        return (new ExpenseExport([$expense]))->download('expenses.pdf');
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['expensecategories'] = ExpenseCategory::pluck('category_name', 'id')->toArray();
    }

}
