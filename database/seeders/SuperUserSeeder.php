<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

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
            'id'                => 1,
            'uuid'              => Str::uuid()->toString(),
            'name'              => 'Admin',
            'email'             => 'admin@gmail.com',
            'password'          => bcrypt('password'),
            'avatar'            => 'avatar.png',
            'phone'             => '0123456789',
            'role_id'           => 1,
            'status'            => 1,
            'is_all_warehouses' => 1,
            'remember_token'    => null,
            'created_at'        => now(),
        ]);

        $superAdmin = Role::create([
            'name'       => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $user->assignRole($superAdmin);
    }
}
