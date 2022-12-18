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
     *
     * @return void
     */
    public function run()
    {
        $warehouses = [
            ['name' => 'default', 'city' => 'casablanca', 'phone' => '061234567896', 'email' => 'default@casa.ma', 'country' => 'morocco'],
            ['name' => 'secend', 'city' => 'casablanca', 'phone' => '061234567898', 'email' => 'secend@casa.ma', 'country' => 'morocco'],

        ];

        collect($warehouses)->each(function ($warehouse) {
            $ware = Warehouse::create($warehouse);

            if (User::first()) {
                $ware->assignedUsers()->sync(User::first());
            }
        });
    }
}
