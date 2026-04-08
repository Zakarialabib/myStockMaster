<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ExpenseForm extends Form
{
    #[Validate('required|string|max:255')]
    public mixed $reference;

    #[Validate('required|integer|exists:expense_categories,id')]
    public mixed $category_id;

    #[Validate('required|date')]
    public ?string $date = null;

    #[Validate('required|numeric')]
    public mixed $amount;

    #[Validate('nullable|string|max:255')]
    public mixed $description;

    #[Validate('nullable|date')]
    public mixed $start_date;

    #[Validate('nullable|date')]
    public mixed $end_date;

    #[Validate('required|in:none,daily,weekly,monthly,yearly')]
    public mixed $frequency = 'none';

    #[Validate('nullable|integer')]
    public ?int $warehouse_id = null;

    #[Validate('nullable|file|mimes:pdf,jpg,jpeg,png|max:2048')]
    public mixed $document;
}
