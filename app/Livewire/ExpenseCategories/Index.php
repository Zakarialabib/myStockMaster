<?php

declare(strict_types=1);

namespace App\Livewire\ExpenseCategories;

use App\Livewire\Utils\Datatable;
use App\Models\ExpenseCategory;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]

class Index extends Component
{
    use Datatable;
    use WithAlert;

    /** @var mixed */
    public $expenseCategory;

    public $model = ExpenseCategory::class;

    public function render()
    {
        abort_if(Gate::denies('expense_categories_access'), 403);

        $query = ExpenseCategory::advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $expenseCategories = $query->paginate($this->perPage);

        return view('livewire.expense-categories.index', ['expenseCategories' => $expenseCategories]);
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('expense_categories_delete'), 403);

        ExpenseCategory::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    #[On('delete')]
    public function delete(ExpenseCategory $expenseCategory): void
    {
        abort_if(Gate::denies('expense_categories_delete'), 403);

        $expenseCategory->delete();

        $this->alert('success', __('Expense Category Deleted Successfully.'));
    }
}
