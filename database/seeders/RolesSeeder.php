<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'id'    => 1,
                'name' => 'Administrator',
                'label' => 'Administrator',
                'description' => 'Administrator',
                'status' => 1,
                'created_at' => now(),
            ]
        ];

        Role::insert($roles);
    }
}
