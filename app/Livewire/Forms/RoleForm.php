<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Role;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RoleForm extends Form
{
    public ?Role $role = null;

    #[Validate('required|string')]
    public string $name = '';

    #[Validate('array')]
    public array $permissions = [];

    public function setRole(Role $role): void
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->permissions = $role->permissions->pluck('id')->map(fn ($id) => (string) $id)->toArray();
    }
}
