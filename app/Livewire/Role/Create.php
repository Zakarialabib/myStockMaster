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

class Create extends Component
{
    use WithAlert;

    public bool $showModal = false;

    #[Validate('required|string|unique:roles,name')]
    public string $name = '';

    #[Validate('array')]
    public array $selectedPermissions = [];

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
        return view('livewire.role.create');
    }

    #[On('createModal')]
    public function createModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        $role = Role::create(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);

        $this->alert('success', __('Role created successfully!'));

        $this->dispatch('refreshIndex')->to(Index::class);
        $this->showModal = false;
        $this->reset(['name', 'selectedPermissions']);
    }
}
