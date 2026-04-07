<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class SupplierForm extends Form
{
    #[Validate('required|string|min:3|max:255', message: 'The name field is required and must be a string between 3 and 255 characters.')]
    public string $name = '';

    #[Validate('nullable|email|max:255', message: 'The email field must be a valid email address with a maximum of 255 characters.')]
    public ?string $email = null;

    #[Validate('required|numeric', message: 'The phone field is required and must be a numeric value.')]
    public mixed $phone;

    #[Validate('nullable|max:255', message: 'The city field must be a string with a maximum of 255 characters.')]
    public ?string $city = null;

    #[Validate('nullable|max:255', message: 'The country field must be a string with a maximum of 255 characters.')]
    public ?string $country = null;

    #[Validate('nullable|max:255', message: 'The address field must be a string with a maximum of 255 characters.')]
    public ?string $address = null;

    #[Validate('nullable|max:255', message: 'The tax number field must be a string with a maximum of 255 characters.')]
    public ?string $tax_number = null;
}
