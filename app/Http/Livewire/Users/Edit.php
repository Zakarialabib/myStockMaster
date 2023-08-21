<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Edit extends Component
{
    use LivewireAlert;

    public $listeners = [
        'editModal',
    ];
    public $editModal = false;

    public $user;

    /** @var array */
    protected $rules = [
        'user.name'     => 'required|string|min:3|max:255',
        'user.email'    => 'required|email|unique:users,email',
        'user.password' => 'required|string|min:8',
        'user.phone'    => 'required|numeric',
    ];

    public function editModal($id)
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->user = User::where('id', $id)->firstOrFail();

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->user->save();

        $this->alert('success', __('User Updated Successfully'));

        $this->editModal = false;
    }

    public function render()
    {
        abort_if(Gate::denies('user_edit'), 403);

        return view('livewire.users.edit');
    }
}
