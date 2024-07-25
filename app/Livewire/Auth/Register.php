<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use App\Enums\Status;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class Register extends Component
{
    #[Validate('required')]
    public $name = '';

    #[Validate('required|email|unique:users,email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    #[Validate('required|min:8|same:passwordConfirmation')]
    public $passwordConfirmation = '';

    #[Validate('required|numeric')]
    public $phone;

    public $city;

    // Set the default city to 'Casablanca'
    public $country; // Set

    public function mount(): void
    {
        $this->city = 'Casablanca';
        $this->country = 'Morocco';
    }

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'phone'    => $this->phone,
            'city'     => $this->city,
            'country'  => $this->country,
            'status'   => Status::INACTIVE, // Set status to inactive by default
        ]);

        $role = Role::where('name', 'admin')->first();

        $user->assignRole($role);

        event(new Registered($user));

        Auth::login($user, true);

        $homePage = match (true) {
            $user->hasRole('admin') => '/admin/dashboard',
            default                 => '/',
        };

        return $this->redirect($homePage, navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
