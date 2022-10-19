<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Customer;

class CustomersSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Customer::create([
            'name' => 'John Doe',
            'email' => 'customer@email.com ',
            'phone' => '212600000000',
            'city' => 'Casablanca',
            'country' => 'Morocco',
            'address' => 'Casablanca, Morocco',
        ]);
    }
}