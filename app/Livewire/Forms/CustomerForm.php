<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CustomerForm extends Form
{
    #[Validate('required', message: 'The name field is required')]
    #[Validate('min:3', message: 'The name field must be more than 3 characters.')]
    #[Validate('max:255', message: 'The name field must be less 255 characters.')]
    public string $name = '';

    public ?string $email = null;

    #[Validate('required', message: 'The phone field is required')]
    #[Validate('numeric', message: 'The phone field must be a numeric value.')]
    public mixed $phone;

    public ?string $city = null;

    public ?string $country = null;

    public ?string $address = null;

    public ?string $tax_number = null;

    public ?int $customer_group_id = null;

    public ?int $role = null;
}
