<?php

declare(strict_types=1);

namespace App\Http\Livewire\Role;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Create extends Component
{
    use LivewireAlert;

    public $role;
    public $selectedPermissions = [];
    public $createModal = false;

    public $listeners = [
        'createModal',
    ];

    protected function rules(): array
    {
        return [
            'role.name'             => 'required|string|min:3|max:255',
            'selectedPermissions.*' => 'exists:permissions,id',
        ];
    }

    public function selectAllPermissions()
    {
        $this->selectedPermissions = $this->permissions->pluck('id')->toArray();
    }

    public function getIsAllSelectedProperty()
    {
        return count($this->selectedPermissions) === count($this->permissions->pluck('id')->toArray());
    }

    public function getIsNoneSelectedProperty()
    {
        return count($this->selectedPermissions) === 0;
    }

    public function deselectAllPermissions()
    {
        $this->selectedPermissions = [];
    }

    public function createModal()
    {
        abort_if(Gate::denies('role_create'), 403);

        $this->resetErrorBag();
        $this->resetValidation();

        $this->role = new Role();
        $this->selectedPermissions = [];

        $this->createModal = true;
    }

    public function store(): void
    {
        $this->validate();

        $this->role->save();
        $this->role->syncPermissions($this->selectedPermissions);

        $this->alert('success', __('Role created successfully.'));

        $this->emit('refreshIndex');

        $this->createModal = false;
    }

    public function getPermissionsProperty()
    {
        return Permission::all();
    }

    public function render()
    {
        return view('livewire.role.create');
    }
}
