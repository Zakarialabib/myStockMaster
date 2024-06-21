<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Models\User;
use App\Rules\MatchCurrentPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class Profile extends Component
{
    use LivewireAlert;

    /** @var mixed */
    public $user;

    #[Validate('required|string|max:255')]

    public $name;

    #[Validate('required|email|unique:users,email')]
    public $email;

    #[Validate('required|numeric')]
    public $phone;

    #[Validate('required|string|min:8')]
    public $password;

    public function mount(): void
    {
        $this->user = User::find(Auth::user()->id);
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
    }

    public function render()
    {
        $this->authorize('users.profile');

        return view('livewire.users.profile');
    }

    public function update(): void
    {
        $this->validate();

        $this->user->update($this->all());

        $this->alert('success', __('Profile updated successfully!'));
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'max:255', new MatchCurrentPassword()],
            'password'         => 'required|min:8|max:255|confirmed',
        ]);

        $this->user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->alert('success', __('Password updated successfully!'));
    }
}
