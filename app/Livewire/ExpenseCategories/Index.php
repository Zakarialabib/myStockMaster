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
    public mixed $expenseCategory;

    public string $model = ExpenseCategory::class;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('expense_categories_access'), 403);

        $query = ExpenseCategory::query()->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $lengthAwarePaginator = $query->paginate($this->perPage);

        return view('livewire.expense-categories.index', ['expenseCategories' => $lengthAwarePaginator]);
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('expense_categories_delete'), 403);

        ExpenseCategory::query()->whereIn('id', $this->selected)->delete();

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
