<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'id'                => 1,
                'name'              => 'Admin',
                'email'             => 'admin1@gmail.com',
                'password'          => bcrypt('password'),
                'avatar'            => 'avatar.png',
                'phone'             => '0123456789',
                'role_id'           => 1,
                'status'            => 1,
                'is_all_warehouses' => 1,
                'remember_token'    => null,
                'created_at'        => now(),
            ],
        ];

        User::insert($users);
    }
}
