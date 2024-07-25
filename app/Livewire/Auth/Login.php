<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Models\User;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

// #[Layout('layouts.guest')]
class Login extends Component
{
    use LivewireAlert;

    #[Validate('required', message: 'Email is required ')]
    #[Validate('email', message: 'Email must be valid')]
    public $email;

    #[Validate('required', message: 'Password is required')]
    public $password;

    #[Validate('boolean')]
    public $remember_me = false;

    public function store()
    {
        $this->validate();

        if (auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->remember_me)) {
            $user = User::where(['email' => $this->email])->first();

            $user->setRememberToken(Str::random(60));

            auth()->login($user, $this->remember_me);

            session()->regenerate();

            return $this->redirect(
                '/admin/dashboard',
                // navigate: true
            );
        }

        $this->alert('error', __('These credentials do not match our records'));
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
