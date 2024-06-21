<?php

declare(strict_types=1);

namespace App\Livewire\ExpenseCategories;

use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    use LivewireAlert;

    public bool $editModal = false;

    public $expenseCategory;

    #[Validate('required', message: 'Please provide a name')]
    #[Validate('min:3', message: 'This name is too short')]
    public string $name;

    public ?string $description = null;

    public function render()
    {
        return view('livewire.expense-categories.edit');
    }

    #[On('editModal')]
    public function editModal($id): void
    {
        abort_if(Gate::denies('expense category_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->expenseCategory = ExpenseCategory::where('id', $id)->firstOrFail();

        $this->name = $this->expenseCategory->name;
        $this->description = $this->expenseCategory->description;

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->expenseCategory->update($this->all());

        $this->alert('success', __('Expense Category Updated Successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->reset(['name', 'description']);

        $this->editModal = false;
    }
}
