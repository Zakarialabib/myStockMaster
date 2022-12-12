<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::all();

        $admin_permissions = $permissions->filter(function ($permission) {
            return substr($permission->title, 0, 7) != 'admin_';
        });
        // TODO: change 1 to Role::ROLE_ADMIN
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));
    }
}
