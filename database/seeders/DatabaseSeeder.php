<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check if comprehensive seeding is requested via environment variable
        $useComprehensiveSeeding = env('COMPREHENSIVE_SEEDING', false);

        if ($useComprehensiveSeeding) {
            $this->runComprehensiveSeeding();
        } else {
            $this->runStandardSeeding();
        }
    }

    /** Run standard seeding (original behavior) */
    private function runStandardSeeding()
    {
        $this->command->info('Running standard database seeding...');

        $this->call([
            RolesAndPermissionsSeeder::class,
            SuperUserSeeder::class,
            CurrencySeeder::class,
            SettingsSeeder::class,
            LanguagesSeeder::class,
            ExpenseSeeder::class,
            CategoriesSeeder::class,
            BrandSeeder::class,
            WarehouseSeeder::class,
            ProductsSeeder::class,
            CustomersSeeder::class,
            SupplierSeeder::class,
            SalesAndPurchasesSeeder::class,
        ]);
    }

    /** Run comprehensive seeding with realistic data */
    private function runComprehensiveSeeding()
    {
        $this->command->info('Running comprehensive database seeding...');

        // First run essential system seeders
        $this->call([
            RolesAndPermissionsSeeder::class,
            SuperUserSeeder::class,
            CurrencySeeder::class,
            SettingsSeeder::class,
            LanguagesSeeder::class,
            ExpenseSeeder::class,
        ]);

        // Then run comprehensive data seeding
        $this->call(ComprehensiveDataSeeder::class);

        // Finally run transaction seeders if they exist
        if (class_exists('Database\\Seeders\\SalesAndPurchasesSeeder')) {
            $this->call(SalesAndPurchasesSeeder::class);
        }
    }
}
