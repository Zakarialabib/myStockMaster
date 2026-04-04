<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    #[Validate('required|string|max:255')]
    public $name;

    #[Validate('required|email')]
    public $email;

    #[Validate('required|string|min:8')]
    public $password;

    #[Validate('required|numeric')]
    public $phone;

    public $city;

    public $country;

    public $address;
}
