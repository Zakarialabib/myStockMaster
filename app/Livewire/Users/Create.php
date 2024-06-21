<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Livewire\Utils\WithModels;
use App\Models\User;
use App\Models\UserWarehouse;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;
    use WithModels;

    public $createModal = false;

    public User $user;

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

    public function render()
    {
        abort_if(Gate::denies('user_create'), 403);

        return view('livewire.users.create');
    }

    #[On('createModal')]
    public function openCreateModal(): void
    {
        abort_if(Gate::denies('user_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        $this->user = User::create($this->all());

        $this->user->assignRole($this->role);

        foreach ($this->warehouse_id as $warehouseId) {
            UserWarehouse::create([
                'user_id'      => $this->user->id,
                'warehouse_id' => $warehouseId,
            ]);
        }

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', 'User created successfully!');

        $this->reset('name', 'email', 'password', 'phone', 'role', 'warehouse_id');

        $this->createModal = false;
    }
}
