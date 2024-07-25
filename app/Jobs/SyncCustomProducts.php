<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SyncCustomProducts implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $product = Product::where('code', $this->data['code'])->first();

        if ($product) {
            // Update existing product
            $product->update([
                'name'        => $this->data['name'],
                'description' => $this->data['description'],
                'price'       => $this->data['price'],
                'old_price'   => $this->data['cost'] ?? null,
                'category_id' => Category::where('name', $this->data['category'])->first()->id ?? Category::create(['name' => $this->data['category']])->id,
                'brand_id'    => $this->data['brand'] ? (Brand::where('name', $this->data['brand'])->first()->id ?? Brand::create(['name' => $this->data['brand']])->id) : null,
                // 'image' => Helpers::uploadImage($this->data['image']) ?? 'default.jpg', // upload from url
                'status'            => 0,
                'barcode_symbology' => 'c128',
                'quantity'          => $this->data['quantity'] ?? 1,
                'unit'              => 'pc', // change this
                'stock_alert'       => $this->data['stock_alert'] ?? 10,
                'tax_amount'        => 0, // change this
                'tax_type'          => 'inclusive', // change this
            ]);
        } else {
            // Create new product
            Product::create([
                'name'        => $this->data['name'],
                'description' => $this->data['description'],
                'price'       => $this->data['price'],
                'old_price'   => $this->data['cost'] ?? null,
                'code'        => $this->data['code'] ?? Str::random(10),
                'category_id' => Category::where('name', $this->data['category'])->first()->id ?? Category::create(['name' => $this->data['category']])->id ?? null,
                'brand_id'    => $this->data['brand'] ? (Brand::where('name', $this->data['brand'])->first()->id ?? Brand::create(['name' => $this->data['brand']])->id) : null,
                // 'image' => Helpers::uploadImage($this->data['image']) ?? 'default.jpg', // upload from url
                'status'            => 0,
                'barcode_symbology' => 'c128',
                'quantity'          => $this->data['quantity'] ?? 1,
                'unit'              => 'pc', // change this
                'stock_alert'       => $this->data['stock_alert'] ?? 10,
                'tax_amount'        => 0, // change this
                'tax_type'          => 'inclusive', // change this
            ]);
        }
    }
}
