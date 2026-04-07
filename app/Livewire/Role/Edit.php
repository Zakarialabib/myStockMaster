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
        $role = Role::query()->findOrFail($id);
        $this->form->setRole($role);
        $this->showModal = true;
    }

    #[Computed]
    public function permission_groups()
    {
        return Permission::all()->groupBy(fn($permission) => explode('_', (string) $permission->name)[0]);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.role.edit');
    }

    public function update(): void
    {
        $this->validate([
            'form.name' => 'required|string|unique:roles,name,' . $this->form->role->id,
            'form.permissions' => 'array',
        ]);

        $this->form->role->update(['name' => $this->form->name]);
        $this->form->role->syncPermissions($this->form->permissions);

        $this->alert('success', __('Role updated successfully!'));

        $this->dispatch('refreshIndex')->to(Index::class);
        $this->showModal = false;
    }
}
