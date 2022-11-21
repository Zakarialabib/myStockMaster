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

    public $listeners = ['confirmDelete', 'delete', 'refreshIndex', 'showModal', 'editModal'];

    public int $selectPage;

    public $showModal;

    public $refreshIndex;

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

    public function refreshIndex()
    {
        $this->resetPage();
    }

    public array $rules = [
        'expenseCategory.name' => 'required',
        'expenseCategory.description' => '',
    ];

    public function mount()
    {
        $this->selectPage = false;
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

    public function showModal(ExpenseCategory $expenseCategory)
    {
        abort_if(Gate::denies('expense_category_show'), 403);

        $this->expenseCategory = ExpenseCategory::find($expenseCategory->id);

        $this->showModal = true;
    }

    public function editModal(ExpenseCategory $expenseCategory)
    {
        abort_if(Gate::denies('expense_category_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->expenseCategory = ExpenseCategory::find($expenseCategory->id);

        $this->editModal = true;
    }

    public function update()
    {
        $this->validate();

        $this->expenseCategory->save();

        $this->alert('success', __('Expense Category Updated Successfully.'));
        
        $this->emit('refreshIndex');
        
        $this->editModal = false;

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

        $this->alert('success', __('Expense Category Deleted Successfully.'));
    }
}
