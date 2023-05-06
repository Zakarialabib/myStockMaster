<?php

declare(strict_types=1);

namespace App\Http\Livewire\Role;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Edit extends Component
{
    public $role;

    public $editModal = false;

    protected function rules(): array
    {
        return [
            'role.name'        => 'required|string|min:3|max:255',
            'role.label'       => 'string|nullable|max:255',
            'role.guard_name'  => 'required|string|max:255',
            'role.description' => 'string|nullable|max:255',
            'role.status'      => 'string|nullable|max:255',
        ];
    }

    public function editModal($role)
    {
        abort_if(Gate::denies('role_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->role = Role::find($role->id);

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->role->save();

        $this->role->permissions()->sync($this->permissions);

        $this->editModal = false;

        $this->alert('success', __('Role updated successfully.'));
    }

    public function getPermissionsProperty(): array
    {
        return Permission::select('name', 'id')->get();
    }

    public function render()
    {
        return view('livewire.role.edit');
    }
}
