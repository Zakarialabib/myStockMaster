<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Livewire\Forms\UserForm;
use App\Livewire\Utils\WithModels;
use App\Models\User;
use App\Services\UserService;
use App\Traits\WithAlert;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Edit extends Component
{
    use WithAlert;
    use WithModels;

    public UserForm $form;

    #[On('editModal')]
    public function openEditModal(mixed $id): void
    {
        abort_if(Gate::denies('user_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $user = User::query()->findOrfail($id);
        $this->form->setUser($user);

        $this->form->showModal = true;
    }

    public function update(UserService $userService): void
    {
        $this->form->validate();

        $userService->updateUser($this->form->user, $this->form->all());

        $this->alert('success', __('User Updated Successfully'));

        $this->form->showModal = false;
    }

    public function render(): View
    {
        return view('livewire.users.edit');
    }
}
