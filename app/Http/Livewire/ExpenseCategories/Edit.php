<?php

declare(strict_types=1);

namespace App\Http\Livewire\ExpenseCategories;

use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Throwable;

class Edit extends Component
{
    use LivewireAlert;

    public $listeners = [
        'editModal',
    ];

    /** @var bool */
    public $editModal = false;

    /** @var mixed */
    public $expenseCategory;

    /** @var array */
    protected $rules = [
        'expenseCategory.name'        => 'required|min:3|max:255',
        'expenseCategory.description' => 'nullable',
    ];

    protected $messages = [
        'expenseCategory.name.required' => 'The name field cannot be empty.',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.expense-categories.edit');
    }

    public function editModal($id): void
    {
        // abort_if(Gate::denies('expense_category_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->expenseCategory = ExpenseCategory::where('id', $id)->firstOrFail();

        $this->editModal = true;
    }

    public function update(): void
    {
        try {
            $validatedData = $this->validate();

            $this->expenseCategory->save($validatedData);

            $this->alert('success', __('Expense Category Updated Successfully.'));

            $this->emit('refreshIndex');

            $this->editModal = false;
        } catch (Throwable $th) {
            $this->alert('error', $th->getMessage());
        }
    }
}
