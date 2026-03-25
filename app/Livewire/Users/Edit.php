<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Livewire\Utils\WithModels;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    use WithModels;

    public $editModal = false;

    public $selectedWarehouses = [];

    public $user;

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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'city' => $this->city,
            'country' => $this->country,
            'address' => $this->address,
        ]);

        if ($this->password && $this->password !== $this->user->password) {
            $this->user->update(['password' => Hash::make($this->password)]);
        }

        $this->user->warehouses()->sync($this->selectedWarehouses);

        $this->alert('success', __('User Updated Successfully'));

        $this->editModal = false;
    }

    public function render(): View
    {
        return view('livewire.users.edit');
    }
}
