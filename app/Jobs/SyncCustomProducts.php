<?php

namespace App\Jobs;

use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncCustomProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        

        try {
            Log::info('Sync begin');
       
        foreach($this->data as $product){
            $categoryName = is_array($product['category']) ? implode(',', $product['category']) : $product['category'];
            $category = Category::where('name', $categoryName)->first();
            if (!$category) {
                $category = Category::create(['name' => $categoryName]);
            }
            Product::updateOrCreate([
                'code' => $product['code'] ?? Str::random(10),
            ], [
                'name' => $product['name'],
                'price' => $product['price'],
                'cost' => $product['price'],
                'code' => $product['code'] ?? Str::random(10),
                'category_id' => $category->id,
                'status' => 0,
                'barcode_symbology' => 'c128',
                'quantity' => $product['quantity'] ?? 1,
                'unit' => 'pc',
                'order_tax' => 0,
                'tax_type' => 1,
                'stock_alert' => 10,
            ]);
        }
           Log::info('Sync finish');
    } catch (\Throwable $th) {
        Log::info('Sync problem'.$th->getMessage());
    }
     
    }
}
