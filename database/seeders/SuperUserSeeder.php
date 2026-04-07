<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'avatar' => 'avatar.png',
            'phone' => '0123456789',
            'role_id' => 1,
            'status' => \App\Enums\Status::ACTIVE,
            'is_all_warehouses' => true,
            'remember_token' => null,
            'created_at' => now(),
        ]);

        $role = Role::where('name', 'admin')->first();

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
