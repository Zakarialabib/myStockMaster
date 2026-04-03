<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    public ?User $user = null;

    #[Validate('required|string|max:255')]
    public $name;

    #[Validate('required|email')]
    public $email;

    #[Validate('nullable|string|min:8')]
    public $password;

    #[Validate('required|numeric')]
    public $phone;

    public $city;

    public $country;

    public $address;

    public $warehouse_id = [];

    public $role;

    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->city = $user->city;
        $this->country = $user->country;
        $this->address = $user->address;
        $this->warehouse_id = $user->warehouses()->pluck('warehouses.id')->toArray();
        $this->role = $user->roles->first()->id ?? null;
    }
}
