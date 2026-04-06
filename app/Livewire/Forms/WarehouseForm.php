<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class WarehouseForm extends Form
{
    #[Validate('string|required|max:255')]
    public string $name = '';

    #[Validate('numeric|nullable|max:255')]
    public ?string $phone = null;

    #[Validate('nullable|max:255')]
    public ?string $country = null;

    #[Validate('nullable|max:255')]
    public ?string $city = null;

    #[Validate('nullable|email|max:255')]
    public ?string $email = null;
}
