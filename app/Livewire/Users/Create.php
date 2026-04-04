<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Livewire\Forms\UserForm;
use App\Livewire\Utils\WithModels;
use App\Models\User;
use App\Models\UserWarehouse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{
    use WithModels;

    public $showModal = false;

    public UserForm $form;

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

        $this->showModal = true;
    }

    public function create(): void
    {
        $this->form->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $this->form->name,
            'email' => $this->form->email,
            'password' => Hash::make($this->form->password),
            'phone' => $this->form->phone,
            'city' => $this->form->city,
            'country' => $this->form->country,
            'address' => $this->form->address,
        ]);

        $user->assignRole($this->form->role);

        foreach ($this->form->warehouse_id as $warehouseId) {
            UserWarehouse::create([
                'user_id' => $user->id,
                'warehouse_id' => $warehouseId,
            ]);
        }

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', 'User created successfully!');

        $this->form->reset();

        $this->showModal = false;
    }
}
