<?php

declare(strict_types=1);

namespace App\Livewire\Role;

use App\Models\Role;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Edit extends Component
{
    use WithAlert;

    public bool $showModal = false;

    public ?Role $role = null;

    #[Validate('required|string')]
    public string $name = '';

    #[Validate('array')]
    public array $selectedPermissions = [];

    #[On('editModal')]
    public function openEditModal(int $id): void
    {
        $this->role = Role::findOrFail($id);
        $this->name = $this->role->name;
        $this->selectedPermissions = $this->role->permissions->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        $this->showModal = true;
    }

    #[Computed]
    public function permissions()
    {
        return Permission::all();
    }

    #[Computed]
    public function isAllSelected(): bool
    {
        return count($this->selectedPermissions) === $this->permissions->count();
    }

    #[Computed]
    public function isNoneSelected(): bool
    {
        return count($this->selectedPermissions) === 0;
    }

    public function selectAllPermissions(): void
    {
        $this->selectedPermissions = $this->permissions->pluck('id')->map(fn ($id) => (string) $id)->toArray();
    }

    public function deselectAllPermissions(): void
    {
        $this->selectedPermissions = [];
    }

    public function render()
    {
        return view('livewire.role.edit');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|unique:roles,name,' . $this->role->id,
        ]);

        $this->role->update(['name' => $this->name]);
        $this->role->syncPermissions($this->selectedPermissions);

        $this->alert('success', __('Role updated successfully!'));

        $this->dispatch('refreshIndex')->to(Index::class);
        $this->showModal = false;
    }
}
