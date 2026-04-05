<?php

declare(strict_types=1);

namespace App\Livewire\Role;

use App\Livewire\Forms\RoleForm;
use App\Models\Role;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

/**
 * @property \Illuminate\Support\Collection $permission_groups
 */
class Edit extends Component
{
    use WithAlert;

    public bool $showModal = false;

    public RoleForm $form;

    #[On('editModal')]
    public function openEditModal(int $id): void
    {
        $role = Role::findOrFail($id);
        $this->form->setRole($role);
        $this->showModal = true;
    }

    #[Computed]
    public function permission_groups()
    {
        return Permission::all()->groupBy(function ($permission) {
            return explode('_', $permission->name)[0];
        });
    }

    public function render()
    {
        return view('livewire.role.edit');
    }

    public function update()
    {
        $this->form->validate([
            'name' => 'required|string|unique:roles,name,' . $this->form->role->id,
            'permissions' => 'array',
        ]);

        $this->form->role->update(['name' => $this->form->name]);
        $this->form->role->syncPermissions($this->form->permissions);

        $this->alert('success', __('Role updated successfully!'));

        $this->dispatch('refreshIndex')->to(Index::class);
        $this->showModal = false;
    }
}
