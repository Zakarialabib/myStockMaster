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
