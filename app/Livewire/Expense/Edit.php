<?php

declare(strict_types=1);

namespace App\Livewire\Expense;

use App\Livewire\Utils\WithModels;
use Livewire\Component;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class Edit extends Component
{
    use LivewireAlert;
    use WithModels;

    /** @var bool */
    public $editModal = false;

    /** @var mixed */
    public $expense;

    #[Validate('required|string|max:255')]
    public $reference;

    #[Validate('required|integer|exists:expense_categories,id')]
    public $category_id;

    #[Validate('required|date')]
    public $date;

    #[Validate('required|numeric')]
    public $amount;

    #[Validate('nullable|string|max:255')]
    public $description;

    public $warehouse_id;

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

        $this->reference = $this->expense->reference;
        $this->category_id = $this->expense->category_id;
        $this->date = $this->expense->date;
        $this->amount = $this->expense->amount;
        $this->description = $this->expense->description;
        $this->warehouse_id = $this->expense->warehouse_id;

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->expense->update([
            'reference'    => $this->reference,
            'category_id'  => $this->category_id,
            'date'         => $this->date,
            'amount'       => $this->amount,
            'description'  => $this->description,
            'warehouse_id' => $this->warehouse_id,
        ]);

        $this->alert('success', __('Expense updated successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->editModal = false;
    }
}
