<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin1@gmail.com',
                'password' => bcrypt('password'),
                'avatar' => 'avatar.png',
                'phone' => '0123456789',
                'role_id' => 1,
                'status' => \App\Enums\Status::ACTIVE,
                'is_all_warehouses' => true,
                'remember_token' => null,
                'created_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::query()->create($user);
        }
    }
}
