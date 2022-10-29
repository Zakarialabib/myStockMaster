<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
            'id'             => 1,
			'name'     => 'Admin',
			'email'      => 'admin@gmail.com',
            'password'       => bcrypt('password'),
            'avatar'        => 'avatar.png',
			'phone'      => '0123456789',
			'role_id'     => 1,
			'statut'    => 1,
            'is_all_warehouses' => 1,
            'remember_token' => null,
            'created_at' => now(),
        ]);

        $superAdmin = Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'admin'
        ]);

        $user->assignRole($superAdmin);
    }
}
