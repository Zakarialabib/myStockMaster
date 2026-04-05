<?php

declare(strict_types=1);

namespace App\Livewire\Expense;

use App\Livewire\Forms\ExpenseForm;
use App\Livewire\Utils\WithModels;
use App\Models\Expense;
use App\Models\ExpenseCategory;
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

    public $editModal = false;

    public $expense;

    public ExpenseForm $form;

    #[Computed]
    public function expenseCategories()
    {
        return ExpenseCategory::select('name', 'id')->get();
    }

    public function render()
    {
        return view('livewire.expense.edit');
    }

    #[On('editModal')]
    public function openEditModal($id): void
    {
        abort_if(Gate::denies('expense_update'), 403);

        $this->resetErrorBag();
        $this->resetValidation();

        $this->expense = Expense::find($id);

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

    public function update(): void
    {
        $this->validate();

        $data = $this->form->all();

        if ($this->form->document) {
            $data['document'] = $this->form->document->store('expenses', 'public');
        } else {
            unset($data['document']); // Do not overwrite existing document if no new file is uploaded
        }

        $this->expense->update($data);

        $this->alert('success', __('Expense updated successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->editModal = false;
    }
}
