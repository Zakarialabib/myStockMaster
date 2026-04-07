<?php

declare(strict_types=1);

namespace App\Livewire\Expense;

use App\Livewire\Forms\ExpenseForm;
use App\Livewire\Utils\WithModels;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Services\ExpenseService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithAlert;
    use WithFileUploads;
    use WithModels;

    public bool $editModal = false;

    public mixed $expense;

    public ExpenseForm $form;

    #[Computed]
    public function expenseCategories()
    {
        return ExpenseCategory::query()->select('name', 'id')->get();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.expense.edit');
    }

    #[On('editModal')]
    public function openEditModal(mixed $id): void
    {
        abort_if(Gate::denies('expense_update'), 403);

        $this->resetErrorBag();
        $this->resetValidation();

        $this->expense = Expense::query()->find($id);

        $this->form->reference = $this->expense->reference;
        $this->form->category_id = $this->expense->category_id;
        $this->form->date = $this->expense->date;
        $this->form->amount = $this->expense->amount;
        $this->form->description = $this->expense->description;
        $this->form->start_date = $this->expense->start_date;
        $this->form->end_date = $this->expense->end_date;
        $this->form->frequency = $this->expense->frequency;
        $this->form->warehouse_id = $this->expense->warehouse_id;

        $this->editModal = true;
    }

    public function update(ExpenseService $expenseService): void
    {
        $this->validate();

        $expenseService->update($this->expense, $this->form->all());

        $this->alert('success', __('Expense updated successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->editModal = false;
    }
}
