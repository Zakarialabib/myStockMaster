<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\ExpenseCategory;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ExpenseCategoryForm extends Form
{
    #[Validate('required|min:3|max:255')]
    public string $name = '';

    #[Validate('nullable|string')]
    public ?string $description = null;

    public function setCategory(ExpenseCategory $expenseCategory): void
    {
        $this->name = $expenseCategory->name;
        $this->description = $expenseCategory->description;
    }
}
