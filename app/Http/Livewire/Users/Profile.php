<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Models\User;
use App\Rules\MatchCurrentPassword;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Profile extends Component
{
    use LivewireAlert;

    public function mount(User $user)
    {
        $this->user = User::find($user->id);
    }

    public function render()
    {
        return view('livewire.users.profile');
    }

    public function update()
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
