<?php

namespace App\Http\Livewire\Expense;

use Livewire\Component;
use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Expense;
use App\Exports\ExpenseExport;

class Index extends Component
{
    use WithPagination, WithSorting, WithConfirmation, WithFileUploads;

    public int $perPage;
    
    public $listeners = ['confirmDelete', 'delete', 'export', 'showModal', 'editModal'];

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public bool $showFilters = false;

    public $export;

    public $selectAll;

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

    public function mount()
    {
        $this->selectAll = false;
        $this->sortField = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Expense())->orderable;
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

    public function showModal(Expense $expense)
    {
        // abort_if(Gate::denies('customer_show'), 403);

        $this->emit('showModal', $expense);
    }

    public function restore(Expense $expense)
    {
        abort_if(Gate::denies('expense_delete'), 403);

        $expense->restore();
    }

    public function forceDelete(Expense $expense)
    {
        abort_if(Gate::denies('expense_delete'), 403);

        $expense->forceDelete();
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

}
