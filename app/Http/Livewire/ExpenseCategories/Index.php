<?php

namespace App\Http\Livewire\ExpenseCategories;

use Livewire\Component;
use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\ExpenseCategory;
use Illuminate\Http\Response;

class Index extends Component
{
    use WithPagination, WithSorting, WithConfirmation, WithFileUploads;

    public int $perPage;

    public $listeners = ['confirmDelete', 'delete'];

    public array $orderable;

    public string $search = '';

    public array $selected = [];

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
        $this->sortField = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new ExpenseCategory())->orderable;
    }

    public function render()
    {
        abort_if(Gate::denies('expense_category_access'), 403);

        $query = ExpenseCategory::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $expenseCategories = $query->paginate($this->perPage);

        return view('livewire.expense-categories.index', compact('expenseCategories'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('expense_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ExpenseCategory::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(ExpenseCategory $expenseCategory)
    {
        abort_if(Gate::denies('expense_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expenseCategory->delete();
    }
}
