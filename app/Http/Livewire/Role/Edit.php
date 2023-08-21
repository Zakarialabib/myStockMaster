<?php

declare(strict_types=1);

namespace App\Http\Livewire\Role;

use App\Models\Role;
use App\Models\Permission;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Edit extends Component
{
    use LivewireAlert;
    public $role;
    public $selectedPermissions = [];

    public $editModal = false;

    public $listeners = [
        'editModal',
    ];

    protected function rules(): array
    {
        return [
            'role.name'             => 'required|string|min:3|max:255',
            'selectedPermissions.*' => 'exists:permissions,id',
        ];
    }

    public function editModal($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->role = Role::find($id);
        $this->selectedPermissions = $this->role->permissions->pluck('id')->toArray();
        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->role->save();
        $this->role->syncPermissions($this->selectedPermissions);

        $this->alert('success', __('Role updated successfully.'));

        $this->editModal = false;

        $this->emit('refreshIndex');
    }

    public function selectAllPermissions()
    {
        $this->selectedPermissions = $this->permissions->pluck('id')->toArray();
    }

    public function deselectAllPermissions()
    {
        $this->selectedPermissions = [];
    }

    public function getIsAllSelectedProperty()
    {
        return count($this->selectedPermissions) === count($this->permissions->pluck('id')->toArray());
    }

    public function getIsNoneSelectedProperty()
    {
        return count($this->selectedPermissions) === 0;
    }

    public function getPermissionsProperty()
    {
        return Permission::select('name', 'id')->get();
    }

    public function render()
    {
        return view('livewire.role.edit');
    }
}
