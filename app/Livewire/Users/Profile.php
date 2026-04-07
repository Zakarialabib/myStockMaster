<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Models\User;
use App\Rules\MatchCurrentPassword;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class Profile extends Component
{
    use WithAlert;

    #[Locked]
    public ?User $user = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|numeric')]
    public string $phone = '';

    public string $password = '';

    public string $current_password = '';

    public string $password_confirmation = '';

    public $role;

    public $is_active;

    public function mount(): void
    {
        $this->authorize('users.profile');

        $this->user = User::find(Auth::user()->id);
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->is_active = $this->user->is_active;
    }

    public function render()
    {
        return view('livewire.users.profile');
    }

    public function update(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'phone' => 'required|numeric',
        ]);

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        $this->alert('success', __('Profile updated successfully!'));
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'max:255', new MatchCurrentPassword],
            'password' => 'required|min:8|max:255|confirmed',
        ]);

        $this->user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->alert('success', __('Password updated successfully!'));
    }
}
