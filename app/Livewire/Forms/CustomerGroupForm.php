<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CustomerGroupForm extends Form
{
    #[Validate('required', message: 'The name field cannot be empty.')]
    #[Validate('min:3', message: 'The name must be at least 3 characters.')]
    #[Validate('max:255', message: 'The name may not be greater than 255 characters.')]
    public string $name = '';

    #[Validate('required', message: 'The percentage field cannot be empty.')]
    public mixed $percentage;
}
