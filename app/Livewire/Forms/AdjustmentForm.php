<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class AdjustmentForm extends Form
{
    #[Validate('required|date')]
    public $date;

    #[Validate('nullable|string|max:1000')]
    public $note;

    #[Validate('required|string|max:255')]
    public $reference;

    #[Validate('required', message: 'Please provide warehouse')]
    public $warehouse_id;
}
