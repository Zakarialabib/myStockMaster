<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ExpenseForm extends Form
{
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

    #[Validate('nullable|date')]
    public $start_date;

    #[Validate('nullable|date')]
    public $end_date;

    #[Validate('required|in:none,daily,weekly,monthly,yearly')]
    public $frequency = 'none';

    #[Validate('nullable|integer')]
    public $warehouse_id;

    #[Validate('nullable|file|mimes:pdf,jpg,jpeg,png|max:2048')]
    public $document;
}
