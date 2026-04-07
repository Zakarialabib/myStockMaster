<?php

declare(strict_types=1);

namespace App\Livewire\ExpenseCategories;

use App\Livewire\Forms\ExpenseCategoryForm;
use App\Models\ExpenseCategory;
use App\Services\ExpenseCategoryService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Edit extends Component
{
    use WithAlert;

    public bool $showModal = false;

    public mixed $expenseCategory;

    public ExpenseCategoryForm $form;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.expense-categories.edit');
    }

    #[On('editModal')]
    public function editModal(mixed $id): void
    {
        abort_if(Gate::denies('expense category_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->expenseCategory = ExpenseCategory::query()->where('id', $id)->firstOrFail();

        $this->form->setCategory($this->expenseCategory);

        $this->showModal = true;
    }

    public function update(ExpenseCategoryService $expenseCategoryService): void
    {
        $this->validate();

        $expenseCategoryService->update($this->expenseCategory, $this->form->all());

        $this->alert('success', __('Expense Category Updated Successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->form->reset();

        $this->showModal = false;
    }
}
