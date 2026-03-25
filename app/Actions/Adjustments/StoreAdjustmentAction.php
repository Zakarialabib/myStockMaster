<?php

declare(strict_types=1);

namespace App\Actions\Adjustments;

use App\Models\AdjustedProduct;
use App\Models\Adjustment;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;

final class StoreAdjustmentAction
{
    public function __invoke(array $adjustmentData, iterable $products): Adjustment
    {
        return DB::transaction(function () use ($adjustmentData, $products): Adjustment {
            $adjustment = Adjustment::create([
                'date' => $adjustmentData['date'],
                'note' => $adjustmentData['note'],
                'user_id' => $adjustmentData['user_id'],
                'warehouse_id' => $adjustmentData['warehouse_id'],
            ]);

            foreach ($products as $product) {
                AdjustedProduct::create([
                    'adjustment_id' => $adjustment->id,
                    'product_id' => $product['id'],
                    'warehouse_id' => $adjustmentData['warehouse_id'],
                    'quantity' => $product['quantities'],
                    'type' => $product['types'],
                ]);

                $productWarehouse = ProductWarehouse::where('product_id', $product['id'])
                    ->where('warehouse_id', $adjustmentData['warehouse_id'])
                    ->firstOrFail();

                if ($product['types'] === 'add') {
                    $productWarehouse->increment('qty', (int) $product['quantities']);
                } else {
                    $productWarehouse->decrement('qty', (int) $product['quantities']);
                }
            }

            return $adjustment;
        });
    }
}
