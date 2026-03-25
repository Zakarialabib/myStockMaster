<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.guest')]
#[Title('Register')]
class Register extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|lowercase|email|max:255|unique:users,email')]
    public string $email = '';

    #[Validate('nullable|string|max:50')]
    public string $phone = '';

    #[Validate(['required', 'string', 'confirmed', 'min:8'])]
    public string $password = '';

    public string $password_confirmation = '';

    /** Handle an incoming registration request. */
    public function register(): void
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone !== '' ? $this->phone : null,
            'password' => Hash::make($this->password),
            'status' => 1,
        ]);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $user->assignRole($adminRole);

        event(new Registered($user));

        Auth::login($user);
        session()->regenerate();

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.auth.register');
    }
}
