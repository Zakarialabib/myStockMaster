<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Currency;

class CurrencyDatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Currency::create([
            'currency_name'      => 'US Dollar',
            'code'               => Str::upper('USD'),
            'symbol'             => '$',
            'thousand_separator' => ',',
            'decimal_separator'  => '.',
            'exchange_rate'      => null
        ]);
    }
}
