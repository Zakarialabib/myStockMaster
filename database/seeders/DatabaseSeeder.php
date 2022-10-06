<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use NumberToWords\Legacy\Numbers\Words\Locale\Cs;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            // UsersSeeder::class,
            // RolesSeeder::class,
            SuperUserSeeder::class,
            RoleUserSeeder::class,
            PermissionsSeeder::class,
            PermissionRoleSeeder::class,
            CurrencySeeder::class,
            SettingsSeeder::class,
        ]);
    }
}
