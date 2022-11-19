<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use App\Models\Wallet;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public $listeners = ['createUser'];

    public $createUser;

    public array $rules = [
        'user.name' => 'required|string|max:255',
        'user.email' => 'required|email|unique:users,email',
        'user.password' => 'required|string|min:8',
        'user.phone' => 'required|numeric',
        'user.city' => 'nullable',
        'user.country' => 'nullable',
        'user.address' => 'nullable',
        'user.tax_number' => 'nullable',
    ];

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.users.create');
    }

    public function createUser()
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createUser = true;
    }

    public function create()
    {
        $this->validate();

        $this->user->save();

        if($this->user) {
            $wallet = Wallet::create([
                'user_id' => $this->user->id,
                'balance' => 0,
            ]);
            $this->alert('success', __('User created successfully!'));
        } else {

            $this->alert('warning', __('User was not created !'));
        }

        $this->emit('userCreated');

        $this->createUser = false;
    }
}
