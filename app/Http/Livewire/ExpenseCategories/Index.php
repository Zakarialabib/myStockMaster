<?php

namespace App\Http\Livewire\ExpenseCategories;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;
use App\Models\ExpenseCategory;
use App\Support\HasAdvancedFilter;

class Index extends Component
{
    use WithPagination, WithSorting, LivewireAlert, HasAdvancedFilter;

    public $expenseCategory;

    public int $perPage;

    public $listeners = ['show','confirmDelete', 'delete', 'createModal', 'showModal', 'editModal'];

    public $show;

    public $showModal;

    public $createModal;

    public $editModal;

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

    public array $rules = [
        'expenseCategory.category_name' => 'required',
        'expenseCategory.category_description' => '',
    ];

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

    public function createModal()
    {
        abort_if(Gate::denies('expense_category_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->createModal = true;
    }

    public function showModal(ExpenseCategory $expenseCategory)
    {
        abort_if(Gate::denies('expense_category_show'), 403);

        $this->expenseCategory = $expenseCategory;

        $this->showModal = true;
    }

    public function editModal(ExpenseCategory $expenseCategory)
    {
        abort_if(Gate::denies('expense_category_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->expenseCategory = $expenseCategory;

        $this->editModal = true;
    }

    public function create()
    {
        $this->validate();

        $this->expenseCategory->save();

        $this->createModal = false;

        $this->alert('success', 'Expense Category Saved Successfully.');
    }

    public function update()
    {
        $this->validate();

        $this->expenseCategory->save();

        $this->editModal = false;

        $this->alert('success', 'Expense Category Updated Successfully.');
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('expense_category_delete'), 403);

        ExpenseCategory::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(ExpenseCategory $expenseCategory)
    {
        abort_if(Gate::denies('expense_category_delete'), 403);

        $expenseCategory->delete();

        $this->alert('success', 'Expense Category Deleted Successfully.');
    }
}
