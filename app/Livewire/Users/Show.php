<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Models\User;
use App\Traits\WithAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    use WithAlert;

    public mixed $user;

    /** @var bool */
    public bool $showModal = false;

    #[On('showModal')]
    public function openShowModal(mixed $id): void
    {
        $this->user = User::query()->find($id);

        $this->showModal = true;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.users.show');
    }
}
