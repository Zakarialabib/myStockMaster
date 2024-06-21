<?php

declare(strict_types=1);

namespace App\Livewire\ExpenseCategories;

use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class Create extends Component
{
    use LivewireAlert;

    public $createModal = false;

    public ExpenseCategory $expenseCategory;

    #[Validate('required|min:3|max:255')]
    public string $name;

    public ?string $description = null;

    protected $messages = [
        'expenseCategory.name.required' => 'The name field cannot be empty.',
    ];

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

        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        ExpenseCategory::create($this->all());

        $this->alert('success', __('Expense created successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->reset(['name', 'description']);

        $this->createModal = false;
    }
}
