<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SyncYouCanProducts implements ShouldQueue
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
        try {
            Log::info('Sync begin');

            foreach ($this->data as $product) {
                Product::updateOrCreate([
                    'code' => $product['id'] ?? Str::random(10),
                ], [
                    'name'              => $product['name'],
                    'price'             => $product['price'],
                    'cost'              => $product['cost_price'],
                    'description'       => $product['description'],
                    'code'              => $product['id'] ?? Str::random(10),
                    'category_id'       => 1,
                    'status'            => $product['visibility'],
                    'barcode_symbology' => 'c128',
                    'quantity'          => $product['inventory'] ?? 1,
                    'unit'              => 'pc',
                    'order_tax'         => 0,
                    'tax_type'          => 1,
                    'stock_alert'       => 10,
                    'created_at'        => $product['created_at'],
                    'updated_at'        => $product['updated_at'],
                ]);
            }
            Log::info('Sync finish');
        } catch (Throwable $th) {
            Log::info('Sync problem'.$th->getMessage());
        }
    }
}
