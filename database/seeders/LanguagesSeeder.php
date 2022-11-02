<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Language::insert([
            [
            'id'           => 1,
            'name'        => 'English',
            'code' => 'en',
            'status'      => 1,
            'is_default'      => 0,
            ],
            [
            'id'           => 2,
            'name'        => 'Arabic',
            'code' => 'ar',
            'status'      => 1,
            'is_default'      => 0,
            ],
            [
            'id'           => 3,
            'name'        => 'French',
            'code' => 'fr',
            'status'      => 1,
            'is_default'      => 1,
            ],
        ]);

    }
}