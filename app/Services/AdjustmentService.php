<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AdjustedProduct;
use App\Models\Adjustment;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;

class AdjustmentService
{
    public function createAdjustment(array $adjustmentData, iterable $products): Adjustment
    {
        return DB::transaction(function () use ($adjustmentData, $products): Adjustment {
            $adjustment = Adjustment::create([
                'reference' => $adjustmentData['reference'],
                'date' => $adjustmentData['date'],
                'note' => $adjustmentData['note'],
                'user_id' => $adjustmentData['user_id'] ?? auth()->id(),
                'warehouse_id' => $adjustmentData['warehouse_id'],
            ]);

            foreach ($products as $product) {
                // Determine quantity and type depending on how the frontend passes it
                $productId = $product['id'] ?? $product['product_id'];
                $quantity = $product['quantities'] ?? $product['quantity'] ?? 1;
                $type = $product['types'] ?? $product['type'] ?? 'add';

                AdjustedProduct::create([
                    'adjustment_id' => $adjustment->id,
                    'product_id' => $productId,
                    'warehouse_id' => $adjustmentData['warehouse_id'],
                    'quantity' => $quantity,
                    'type' => $type,
                ]);

                $productWarehouse = ProductWarehouse::where('product_id', $productId)
                    ->where('warehouse_id', $adjustmentData['warehouse_id'])
                    ->firstOrFail();

                if ($type === 'add') {
                    $productWarehouse->increment('qty', (int) $quantity);
                } else {
                    $productWarehouse->decrement('qty', (int) $quantity);
                }
            }

            return $adjustment;
        });
    }

    public function updateAdjustment(Adjustment $adjustment, array $adjustmentData, iterable $products): Adjustment
    {
        return DB::transaction(function () use ($adjustment, $adjustmentData, $products): Adjustment {
            // Revert previous stock changes
            foreach ($adjustment->adjustedProducts as $existingProduct) {
                $productWarehouse = ProductWarehouse::where('product_id', $existingProduct->product_id)
                    ->where('warehouse_id', $existingProduct->warehouse_id)
                    ->first();

                if ($productWarehouse) {
                    if ($existingProduct->type === 'add') {
                        $productWarehouse->decrement('qty', (int) $existingProduct->quantity);
                    } else {
                        $productWarehouse->increment('qty', (int) $existingProduct->quantity);
                    }
                }
            }

            // Delete old adjusted products
            $adjustment->adjustedProducts()->delete();

            // Update adjustment record
            $adjustment->update([
                'reference' => $adjustmentData['reference'],
                'date' => $adjustmentData['date'],
                'note' => $adjustmentData['note'],
                'user_id' => $adjustmentData['user_id'] ?? auth()->id(),
                'warehouse_id' => $adjustmentData['warehouse_id'],
            ]);

            // Apply new stock changes
            foreach ($products as $product) {
                $productId = $product['id'] ?? $product['product_id'];
                $quantity = $product['quantities'] ?? $product['quantity'] ?? 1;
                $type = $product['types'] ?? $product['type'] ?? 'add';

                AdjustedProduct::create([
                    'adjustment_id' => $adjustment->id,
                    'product_id' => $productId,
                    'warehouse_id' => $adjustmentData['warehouse_id'],
                    'quantity' => $quantity,
                    'type' => $type,
                ]);

                $productWarehouse = ProductWarehouse::where('product_id', $productId)
                    ->where('warehouse_id', $adjustmentData['warehouse_id'])
                    ->first();

                if ($productWarehouse) {
                    if ($type === 'add') {
                        $productWarehouse->increment('qty', (int) $quantity);
                    } else {
                        $productWarehouse->decrement('qty', (int) $quantity);
                    }
                }
            }

            return $adjustment;
        });
    }
}
