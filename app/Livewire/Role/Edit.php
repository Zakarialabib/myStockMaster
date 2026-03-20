<?php

declare(strict_types=1);

namespace App\Livewire\Role;

// use App\Models\Permission;
use Spatie\Permission\Models\Permission;
use App\Traits\WithAlert;
// use App\Models\Role;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    use WithAlert;

    #[Validate([
        'role.title'    => 'required|string',
        'permissions'   => 'required|array',
        'permissions.*' => 'integer|exists:permissions,id',
    ])]
    public Role $role;

    public array $permissions = [];

    public array $listsForFields = [];

    public function mount(Role $role): void
    {
        $this->role = $role;
        $this->permissions = $this->role->permissions->pluck('id')->toArray();
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.role.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->role->save();
        $this->role->syncPermissions($this->permissions);

        // $this->alert('success', __('Role updated successfully!') );

        return redirect()->route('roles.index');
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['permissions'] = Permission::pluck('title', 'id')->toArray();
    }
}
