<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\On;

class Show extends Component
{
    public $user;

    /** @var bool */
    public $showModal = false;

    #[On('showModal')]
    public function openShowModal($id): void
    {
        $this->user = User::find($id);

        $this->showModal = true;
    }

    public function render()
    {
        return view('livewire.users.show');
    }
}
