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

class Create extends Component
{
    use WithAlert;

    public bool $showModal = false;

    public RoleForm $form;

    #[Computed]
    public function permission_groups()
    {
        return Permission::all()->groupBy(function ($permission) {
            return explode('_', $permission->name)[0];
        });
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
        $this->form->reset();
        $this->showModal = true;
    }

    public function store()
    {
        $this->form->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $this->form->name]);
        $role->syncPermissions($this->form->permissions);

        $this->alert('success', __('Role created successfully!'));

        $this->dispatch('refreshIndex')->to(Index::class);
        $this->showModal = false;
        $this->form->reset();
    }
}
