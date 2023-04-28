<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Models\User;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = ['createModal'];

    /** @var bool */
    public $createModal = false;

    /** @var mixed */
    public $user;

    /** @var array */
    protected $rules = [
        'user.name'     => 'required|string|min:3|max:255',
        'user.email'    => 'required|email|unique:users,email',
        'user.password' => 'required|string|min:8',
        'user.phone'    => 'required|numeric',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.users.create');
    }

    public function createModal(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->user = new User();

        $this->createModal = true;
    }

    public function create(): void
    {
        try {
            $validatedData = $this->validate();

            $this->user->save($validatedData);

            $this->alert('success', __('User created successfully!'));

            $this->emit('refreshIndex');

            $this->createModal = false;
        } catch (Throwable $th) {
            $this->alert('error', $th->getMessage());
        }
    }
}
