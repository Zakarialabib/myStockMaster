<?php

declare(strict_types=1);

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ComprehensiveDataSeeder extends Seeder
{
    /**
     * Run the comprehensive database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting comprehensive data seeding...');

        // Disable foreign key checks for faster seeding
        Schema::disableForeignKeyConstraints();

        try {
            // Clear existing data (optional - comment out if you want to keep existing data)
            $this->clearExistingData();

            // Seed comprehensive data in proper order
            $this->seedComprehensiveData();

            $this->command->info('Comprehensive data seeding completed successfully!');
        } catch (Exception $e) {
            $this->command->error('Error during seeding: ' . $e->getMessage());

            throw $e;
        } finally {
            // Re-enable foreign key checks
            Schema::enableForeignKeyConstraints();
        }
    }

    private function clearExistingData()
    {
        $this->command->info('Clearing existing data...');

        // Clear in reverse dependency order
        $tables = [
            'product_warehouse',
            'products',
            'customers',
            'suppliers',
            'brands',
            'categories',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->command->info("Cleared {$table} table");
            }
        }
    }

    private function seedComprehensiveData()
    {
        $this->command->info('Seeding comprehensive suppliers...');
        $this->call(ComprehensiveSupplierSeeder::class);

        $this->command->info('Seeding comprehensive customers...');
        $this->call(ComprehensiveCustomerSeeder::class);

        $this->command->info('Seeding comprehensive products...');
        $this->call(ComprehensiveProductSeeder::class);

        $this->command->info('Creating sample transactions...');
        $this->createSampleTransactions();
    }

    private function createSampleTransactions()
    {
        // This method can be expanded to create sample sales, purchases, etc.
        // For now, we'll just log that this step is available for future enhancement
        $this->command->info('Sample transactions creation is available for future enhancement');

        // Example of what could be added:
        // $this->call(SampleSalesSeeder::class);
        // $this->call(SamplePurchasesSeeder::class);
        // $this->call(SampleQuotationsSeeder::class);
    }

    /** Get seeding statistics */
    public function getStatistics()
    {
        $stats = [
            'categories' => DB::table('categories')->count(),
            'brands' => DB::table('brands')->count(),
            'products' => DB::table('products')->count(),
            'customers' => DB::table('customers')->count(),
            'suppliers' => DB::table('suppliers')->count(),
            'warehouses' => DB::table('warehouses')->count(),
            'product_warehouse_relations' => DB::table('product_warehouse')->count(),
        ];

        $this->command->info('Seeding Statistics:');

        foreach ($stats as $table => $count) {
            $this->command->info("  {$table}: {$count} records");
        }

        return $stats;
    }
}
