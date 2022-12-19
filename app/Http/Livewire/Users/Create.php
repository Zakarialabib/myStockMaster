<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Models\User;
use App\Models\Wallet;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Create extends Component
{
    use LivewireAlert;

    /** @var string[] */
    public $listeners = ['createModal'];

    /** @var bool */
    public $createModal = false;

    /** @var mixed */
    public $user;

    public array $rules = [
        'user.name'       => 'required|string|max:255',
        'user.email'      => 'required|email|unique:users,email',
        'user.password'   => 'required|string|min:8',
        'user.phone'      => 'required|numeric',
        'user.city'       => 'nullable',
        'user.country'    => 'nullable',
        'user.address'    => 'nullable',
        'user.tax_number' => 'nullable',
    ];

    public function mount(User $user): void
    {
        $this->user = $user;
    }

    public function render(): View|Factory
    {
        return view('livewire.users.create');
    }

    public function createModal(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        $this->user->save();

        if ($this->user) {
            $wallet = Wallet::create([
                'user_id' => $this->user->id,
                'balance' => 0,
            ]);
            $this->alert('success', __('User created successfully!'));
        } else {
            $this->alert('warning', __('User was not created !'));
        }

        $this->emit('userCreated');

        $this->createModal = false;
    }
}
