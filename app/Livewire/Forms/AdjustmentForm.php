<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class AdjustmentForm extends Form
{
    #[Validate('required|date')]
    public ?string $date = null;

    #[Validate('nullable|string|max:1000')]
    public mixed $note;

    #[Validate('required|string|max:255')]
    public mixed $reference;

    #[Validate('required', message: 'Please provide warehouse')]
    public ?int $warehouse_id = null;
}
