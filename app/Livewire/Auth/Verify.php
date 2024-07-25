<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Verify extends Component
{
    use LivewireAlert;

    public function sendVerification(): void
    {
        if (auth()->user()->hasVerifiedEmail()) {
            $this->redirect(
                session('url.intended', '/admin/dashboard'),
                navigate: true
            );

            return;
        }

        auth()->user()->sendEmailVerificationNotification();
        session()->flash('status', 'verification-link-sent');
    }

    public function logout(): void
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.verify');
    }
}
