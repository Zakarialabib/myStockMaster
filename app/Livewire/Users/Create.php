<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Livewire\Forms\UserForm;
use App\Livewire\Utils\WithModels;
use App\Services\UserService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{
    use WithAlert;
    use WithModels;

    public UserForm $form;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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

        $this->form->showModal = true;
    }

    public function create(UserService $userService): void
    {
        $this->form->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $userService->createUser($this->form->all());

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', 'User created successfully!');

        $this->form->reset();

        $this->form->showModal = false;
    }
}
