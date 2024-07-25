<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Livewire\Utils\WithModels;
use App\Models\User;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class Edit extends Component
{
    use LivewireAlert;
    use WithModels;

    public $editModal = false;

    public $selectedWarehouses = [];

    public $user;

    #[Validate('required|string|max:255')]
    public $name;

    #[Validate('required|email|unique:users,email')]
    public $email;

    #[Validate('required|string|min:8')]
    public $password;

    #[Validate('required|numeric')]
    public $phone;

    public $city;

    public $country;

    public $address;

    public $warehouse_id = [];

    public $role;

    #[On('editModal')]
    public function openEditModal($id): void
    {
        abort_if(Gate::denies('user_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->user = User::findOrfail($id);

        $this->name = $this->user->name;

        $this->email = $this->user->email;

        $this->password = $this->user->password;

        $this->phone = $this->user->phone;

        $this->city = $this->user->city;

        $this->country = $this->user->country;

        $this->address = $this->user->address;

        $this->selectedWarehouses = $this->user->warehouses()->pluck('warehouses.id')->toArray();
        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->user->update([
            'name'     => $this->user->name,
            'email'    => $this->user->email,
            'password' => Hash::make('password'),
            'phone'    => $this->user->phone,
            'city'     => $this->user->city,
            'country'  => $this->user->country,
            'address'  => $this->user->address,
        ]);

        $this->user->warehouses()->sync($this->selectedWarehouses);

        $this->alert('success', __('User Updated Successfully'));

        $this->editModal = false;
    }

    public function render(): View
    {
        return view('livewire.users.edit');
    }
}
