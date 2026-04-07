<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    public ?User $user = null;

    public mixed $showModal = false;

    #[Validate('required|string|max:255')]
    public mixed $name;

    #[Validate('required|email')]
    public mixed $email;

    #[Validate('nullable|string|min:8')]
    public mixed $password;

    #[Validate('required|numeric')]
    public mixed $phone;

    public mixed $city;

    public mixed $country;

    public mixed $address;

    public mixed $warehouse_id = [];

    public mixed $role;

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
