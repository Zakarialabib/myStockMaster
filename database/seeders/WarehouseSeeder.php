<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            ['name' => 'warehouse 1', 'city' => 'casablanca', 'phone' => '061234567896', 'email' => 'warehouse1@casa.ma', 'country' => 'morocco'],
            ['name' => 'warehouse 2', 'city' => 'casablanca', 'phone' => '061234567898', 'email' => 'warehouse2@casa.ma', 'country' => 'morocco'],
        ];

        collect($warehouses)->each(function ($warehouse): void {
            $ware = Warehouse::query()->create($warehouse);

            if (User::query()->first()) {
                $ware->users()->attach(User::query()->first()->id);
            }
        });
    }
}
