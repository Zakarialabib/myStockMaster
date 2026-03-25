<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure we have at least one warehouse
        $warehouses = Warehouse::all();

        if ($warehouses->isEmpty()) {
            $warehouses = collect([
                Warehouse::create([
                    'name' => 'Main Warehouse',
                    'city' => 'Default City',
                    'address' => '123 Main Street',
                    'phone' => '+1234567890',
                    'email' => 'warehouse@example.com',
                    'country' => 'USA',
                    'status' => true,
                ]),
            ]);
        }

        // Ensure we have at least one category
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $categories = collect([
                Category::create([
                    'name' => 'General',
                    'code' => 'GEN001',
                ]),
            ]);
        }

        // Create products using direct DB insert to avoid global scopes and observers
        $products = [];

        for ($i = 0; $i < 25; $i++) {
            $productId = (string) Str::uuid();
            $productData = [
                'id' => $productId,
                'name' => fake()->words(2, true),
                'category_id' => $categories->random()->id,
                'featured' => fake()->boolean(20),
                'code' => 'PRD' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                'quantity' => fake()->numberBetween(50, 200),
                'unit' => fake()->randomElement(['pcs', 'kg', 'ltr', 'box']),
                'status' => true,
                'slug' => Str::slug(fake()->words(2, true)),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('products')->insert($productData);
            $products[] = array_merge($productData, ['id' => $productId]);
        }

        // Create product-warehouse relationships
        foreach ($products as $product) {
            foreach ($warehouses as $warehouse) {
                ProductWarehouse::create([
                    'product_id' => $product['id'],
                    'warehouse_id' => $warehouse->id,
                    'qty' => fake()->numberBetween(10, 100),
                    'price' => fake()->randomFloat(2, 10.00, 100.00),
                    'cost' => fake()->randomFloat(2, 5.00, 80.00),
                    'old_price' => fake()->randomFloat(2, 12.00, 120.00),
                    'stock_alert' => fake()->numberBetween(5, 20),
                    'is_ecommerce' => fake()->boolean(30),
                ]);
            }
        }
    }
}
