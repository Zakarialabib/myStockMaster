<?php

declare(strict_types=1);

namespace App\Livewire\ExpenseCategories;

use App\Livewire\Forms\ExpenseCategoryForm;
use App\Services\ExpenseCategoryService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{
    use WithAlert;

    public $showModal = false;

    public ExpenseCategoryForm $form;

    public function render()
    {
        abort_if(Gate::denies('expense_categories_create'), 403);

        return view('livewire.expense-categories.create');
    }

    #[On('createModal')]
    public function openModal(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->showModal = true;
    }

    public function create(ExpenseCategoryService $expenseCategoryService): void
    {
        $this->validate();

        $expenseCategoryService->create($this->form->all());

        $this->alert('success', __('Expense Category created successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->form->reset();

        $this->showModal = false;
    }
}
