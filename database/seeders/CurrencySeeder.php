<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'name' => 'Dirham Marocain',
                'code' => Str::upper('MAD'),
                'locale' => 'fr_MA',
            ],
            [
                'name' => 'Euro',
                'code' => Str::upper('EUR'),
                'locale' => 'fr_FR',
            ],
            [
                'name' => 'Dollar',
                'code' => Str::upper('USD'),
                'locale' => 'en_US',
            ],
            [
                'name' => 'Pound',
                'code' => Str::upper('GBP'),
                'locale' => 'en_GB',
            ],
            [
                'name' => 'Turkish Lira',
                'code' => Str::upper('TRY'),
                'locale' => 'tr_TR',
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::query()->create($currency);
        }
    }
}
