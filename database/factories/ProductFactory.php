<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'              => 'iPhone 12',
            'uuid'              => fake()->uuid(),
            'code'              => Str::random(5),
            'category_id'       => 1,
            'cost'              => 1000,
            'price'             => 1000,
            'quantity'          => 10,
            'unit'              => 'pcs',
            'note'              => 'iPhone 12',
            'image'             => 'https://www.apple.com/v/iphone/home/ah/images/overview/compare/compare_iphone_12__f2x.png',
            'barcode_symbology' => 'C39',
            'stock_alert'       => 10,
            'order_tax'         => 0,
            'tax_type'          => 0,
        ];
    }
}
