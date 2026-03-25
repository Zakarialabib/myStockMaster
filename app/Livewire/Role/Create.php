<?php

declare(strict_types=1);

namespace App\Livewire\Role;

// use App\Models\Permission;
use App\Traits\WithAlert;
use Livewire\Attributes\Validate;
// use App\Models\Role;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Create extends Component
{
    use WithAlert;

    #[Validate([
        'role.title' => 'required|string',
        'permissions' => 'required|array',
        'permissions.*' => 'integer|exists:permissions,id',
    ])]
    public Role $role;

    public array $permissions = [];

    public array $listsForFields = [];

    public function mount(): void
    {
        $this->role = new Role;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.role.create');
    }

    public function submit()
    {
        $this->validate();

        $this->role->save();

        $this->role->givePermissionTo($this->permissions);

        // $this->alert('success', __('Role created successfully!') );

        return redirect()->route('roles.index');
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['permissions'] = Permission::pluck('title', 'id')->toArray();
    }
}
