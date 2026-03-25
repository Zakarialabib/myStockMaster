<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            [
                'id' => 1,
                'name' => 'English',
                'code' => 'en',
                'status' => \App\Enums\Status::ACTIVE,
                'is_default' => false,
            ],
            [
                'id' => 2,
                'name' => 'Arabic',
                'code' => 'ar',
                'status' => \App\Enums\Status::ACTIVE,
                'is_default' => false,
            ],
            [
                'id' => 3,
                'name' => 'French',
                'code' => 'fr',
                'status' => \App\Enums\Status::ACTIVE,
                'is_default' => true,
            ],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
