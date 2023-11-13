<?php

declare(strict_types=1);

namespace App\Http\Livewire\ExpenseCategories;

use App\Http\Livewire\WithSorting;
use App\Models\ExpenseCategory;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use Datatable;

    /** @var mixed */
    public $expenseCategory;

    public $showModal = false;

    /** @var array<string> */
    public $listeners = [
        'showModal',
        'refreshIndex' => '$refresh',
        'delete',
    ];

    /** @var array<array<string>> */
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

    public function mount(): void
    {
        $this->selectPage = false;
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new ExpenseCategory())->orderable;
    }

    public function render()
    {
        abort_if(Gate::denies('expense_categories_access'), 403);

        $query = ExpenseCategory::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $expenseCategories = $query->paginate($this->perPage);

        return view('livewire.expense-categories.index', compact('expenseCategories'));
    }

    public function showModal($id): void
    {
        abort_if(Gate::denies('expense_categories_show'), 403);

        $this->expenseCategory = ExpenseCategory::where('id', $id)->get();

        $this->showModal = true;
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('expense_categories_delete'), 403);

        ExpenseCategory::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(ExpenseCategory $expenseCategory): void
    {
        abort_if(Gate::denies('expense_categories_delete'), 403);

        $expenseCategory->delete();

        $this->alert('success', __('Expense Category Deleted Successfully.'));
    }
}
