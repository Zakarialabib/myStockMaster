<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Livewire\Forms\UserForm;
use App\Livewire\Utils\WithModels;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Component;

class Edit extends Component
{
    use WithModels;

    public $showModal = false;

    public UserForm $form;

    #[On('editModal')]
    public function openEditModal($id): void
    {
        abort_if(Gate::denies('user_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $user = User::findOrfail($id);
        $this->form->setUser($user);

        $this->showModal = true;
    }

    public function update(): void
    {
        $this->form->validate();

        $this->form->user->update([
            'name' => $this->form->name,
            'email' => $this->form->email,
            'phone' => $this->form->phone,
            'city' => $this->form->city,
            'country' => $this->form->country,
            'address' => $this->form->address,
        ]);

        if ($this->form->password && $this->form->password !== $this->form->user->password) {
            $this->form->user->update(['password' => Hash::make($this->form->password)]);
        }

        $this->form->user->warehouses()->sync($this->form->warehouse_id);

        if ($this->form->role) {
            $this->form->user->syncRoles($this->form->role);
        }

        $this->alert('success', __('User Updated Successfully'));

        $this->showModal = false;
    }

    public function render(): View
    {
        return view('livewire.users.edit');
    }
}
