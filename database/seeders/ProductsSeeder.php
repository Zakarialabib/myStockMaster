<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
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
        Product::create([
            'name' => 'iPhone 12',
            'code' => Str::random(5),
            'category_id' => 1,
            'cost' => 1000,
            'price' => 1000,
            'quantity' => 10,
            'unit' => 'pcs',
            'note' => 'iPhone 12',
            'image' => 'https://www.apple.com/v/iphone/home/ah/images/overview/compare/compare_iphone_12__f2x.png',
            'barcode_symbology' => 'C39',
            'stock_alert' => 10,
            'order_tax' => 0,
            'tax_type' => 0,
        ]);
    }
}
