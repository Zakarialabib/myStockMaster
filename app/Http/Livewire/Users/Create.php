<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Models\User;
use App\Models\UserWarehouse;
use App\Models\Role;
use App\Models\Warehouse;
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

    public $name;

    public $email;

    public $password;

    public $phone;

    public $role;

    public $warehouse_id;

    /** @var array */
    protected $rules = [
        'name'         => 'required|string|min:3|max:255',
        'email'        => 'required|email|unique:users,email',
        'password'     => 'required|string|min:8',
        'phone'        => 'required|numeric',
        'role'         => 'required',
        'warehouse_id' => 'required|array',
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

        $this->createModal = true;
    }

    public function create(): void
    {
        try {
            $validatedData = $this->validate();

            $user = User::create($validatedData);

            $user->assignRole($this->role);

            foreach ($this->warehosues_id as $warehouseId) {
                UserWarehouse::create([
                    'user_id'      => $user->id,
                    'warehouse_id' => $warehouseId,
                ]);
            }

            $this->alert('success', __('User created successfully!'));

            $this->emit('refreshIndex');

            $this->reset('name', 'email', 'password', 'phone', 'role', 'warehouse_id');

            $this->createModal = false;
        } catch (Throwable $th) {
            $this->alert('error', $th->getMessage());
        }
    }

    public function getRolesProperty()
    {
        return Role::pluck('name', 'id')->toArray();
    }

    public function getWarehousesProperty()
    {
        return Warehouse::pluck('name', 'id')->toArray();
    }
}
