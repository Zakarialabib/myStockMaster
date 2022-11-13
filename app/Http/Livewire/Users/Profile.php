<?php

namespace App\Http\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Profile extends Component
{
    use LivewireAlert;
    
    public function mount(User $user)
    {
        $this->user = $user;
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
            'email' => $this->email
        ]);

        if ($this->image) {
            $this->image->store('users', 'public');
            auth()->user()->update([
                'image' => $this->image->hashName()
            ]);
        }

        $this->alert('success', 'Profile updated successfully!');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'max:255', new MatchCurrentPassword()],
            'password' => 'required|min:8|max:255|confirmed'
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->password)
        ]);

        $this->alert('success', 'Password updated successfully!');
    }
}
