<?php

declare(strict_types=1);

namespace App\Livewire\Role;

// use App\Models\Permission;
use Spatie\Permission\Models\Permission;
// use App\Models\Role;
use Spatie\Permission\Models\Role;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Edit extends Component
{
    use LivewireAlert;

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

    protected function rules(): array
    {
        return [
            'role.title' => [
                'string',
                'required',
            ],
            'permissions' => [
                'required',
                'array',
            ],
            'permissions.*.id' => [
                'integer',
                'exists:permissions,id',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['permissions'] = Permission::pluck('title', 'id')->toArray();
    }
}
