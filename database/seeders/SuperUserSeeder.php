<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'id'                => Str::uuid(),
            'name'              => 'Admin',
            'email'             => 'admin@gmail.com',
            'password'          => Hash::make('password'),
            'avatar'            => 'avatar.png',
            'phone'             => '0123456789',
            'role_id'           => 1,
            'status'            => 1,
            'is_all_warehouses' => 1,
            'remember_token'    => null,
            'created_at'        => now(),
        ]);

        $role = Role::where('name', 'admin')->first();

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
