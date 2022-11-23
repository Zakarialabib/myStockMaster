<?php

namespace App\Http\Livewire\Expense;

use App\Exports\ExpenseExport;
use App\Http\Livewire\WithSorting;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Support\HasAdvancedFilter;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use HasAdvancedFilter;

    public $expense;

    public int $perPage;

    public int $selectPage;

    public $listeners = ['confirmDelete', 'exportAll', 'downloadAll', 'delete', 'export', 'refreshIndex', 'showModal', 'editModal'];

    public $showModal;

    public $editModal;

    public $refreshIndex;

    public $export;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public bool $showFilters = false;

    public array $listsForFields = [];

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

    public function refreshIndex()
    {
        $this->resetPage();
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
        $this->orderable = (new Expense)->orderable;
        $this->initListsForFields();
    }

    public function render()
    {
        abort_if(Gate::denies('expense_access'), 403);

        $query = Expense::with(['category', 'user', 'warehouse'])
                         ->advancedFilter([
                             's' => $this->search ?: null,
                             'order_column' => $this->sortBy,
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

    public function showModal(Expense $expense)
    {
        abort_if(Gate::denies('expense_show'), 403);

        $this->expense = Expense::find($expense->id);

        $this->showModal = true;
    }

    public function editModal(Expense $expense)
    {
        abort_if(Gate::denies('expense_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->expense = Expense::find($expense->id);

        $this->editModal = true;
    }

    public function update()
    {
        $this->validate();

        $this->expense->save();

        $this->alert('success', __('Expense updated successfully.'));

        $this->emit('refreshIndex');

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

        return (new ExpenseExport([$expense]))->download('expenses.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['expensecategories'] = ExpenseCategory::pluck('name', 'id')->toArray();
    }
}
