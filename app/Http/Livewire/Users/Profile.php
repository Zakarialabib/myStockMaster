<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Models\User;
use App\Rules\MatchCurrentPassword;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    use LivewireAlert;

    /** @var mixed */
    public $user;

    public $name;
    public $email;
    public $image;
    public $password;

    public array $rules = [
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'phone'    => 'required|numeric',
    ];

    public function mount(): void
    {
        $this->user = User::find(Auth::user()->id);
    }

    public function render(): View|Factory
    {
        return view('livewire.users.profile');
    }

    public function update(): void
    {
        $this->validate();

        auth()->user()->update([
            'name'  => $this->name,
            'email' => $this->email,
        ]);

        if (isset($this->image)) {
            $this->image->store('users', 'public');
            auth()->user()->update([
                'image' => $this->image->hashName(),
            ]);
        }

        $this->alert('success', __('Profile updated successfully!'));
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'max:255', new MatchCurrentPassword()],
            'password'         => 'required|min:8|max:255|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->alert('success', __('Password updated successfully!'));
    }
}
