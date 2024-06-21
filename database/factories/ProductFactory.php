<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\ProductWarehouse;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id'                => Str::uuid(),
            'name'              => $this->faker->name,
            'code'              => Str::random(5),
            'category_id'       => 1,
            'brand_id'          => null,
            'slug'              => Str::slug($this->faker->name),
            'unit'              => 'pcs',
            'description'       => $this->faker->sentence,
            'image'             => null, // uploadImage('images/products', '1000', '1000'),
            'gallery'           => null,
            'barcode_symbology' => 'C39',
            'tax_amount'        => 0,
            'tax_type'          => 0,
            'featured'          => true,
            'best'              => true,
            'hot'               => true,
            'status'            => true,
            'embeded_video'     => null,
            'options'           => null,
            'usage'             => null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            $warehouses = Warehouse::inRandomOrder()->limit(2)->get();

            foreach ($warehouses as $warehouse) {
                ProductWarehouse::create([
                    'product_id'    => $product->id,
                    'warehouse_id'  => $warehouse->id,
                    'qty'           => 20,
                    'cost'          => 250,
                    'price'         => 1000,
                    'old_price'     => 500,
                    'is_ecommerce'  => true,
                    'stock_alert'   => 10,
                    'is_discount'   => true,
                    'discount_date' => now(),
                ]);
            }
        });
    }
}
