<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ProductWarehouse;
use App\Models\Transfer;
use App\Models\TransferDetails;
use Illuminate\Support\Facades\DB;

class TransferService
{
    public function createTransfer(array $data, iterable $products): Transfer
    {
        return DB::transaction(function () use ($data, $products) {
            $transfer = Transfer::query()->create([
                'reference' => $data['reference'],
                'date' => $data['date'],
                'user_id' => $data['user_id'] ?? auth()->id(),
                'from_warehouse_id' => $data['from_warehouse_id'],
                'to_warehouse_id' => $data['to_warehouse_id'],
                'total_qty' => $data['total_qty'],
                'item' => count($products),
                'total_tax' => 0,
                'total_cost' => $data['total_cost'],
                'total_amount' => $data['total_amount'],
                'shipping' => $data['shipping_amount'] ?? 0,
                'document' => $data['document'] ?? null,
                'status' => $data['status'] ?? 1,
                'note' => $data['note'] ?? null,
            ]);

            foreach ($products as $product) {
                $qty = $product['quantities'] ?? $product['quantity'] ?? 1;

                TransferDetails::query()->create([
                    'transfer_id' => $transfer->id,
                    'product_id' => $product['id'] ?? $product['product_id'],
                    'warehouse_id' => $data['to_warehouse_id'],
                    'quantity' => $qty,
                ]);

                // Decrement the source ProductWarehouse
                ProductWarehouse::query()->where('product_id', $product['id'] ?? $product['product_id'])
                    ->where('warehouse_id', $data['from_warehouse_id'])
                    ->decrement('qty', $qty);

                // Increment the destination ProductWarehouse
                $destProductWarehouse = ProductWarehouse::query()->firstOrCreate([
                    'product_id' => $product['id'] ?? $product['product_id'],
                    'warehouse_id' => $data['to_warehouse_id'],
                ], [
                    'price' => $product['price'] ?? 0,
                    'cost' => $product['cost'] ?? 0,
                    'qty' => 0,
                ]);

                $destProductWarehouse->increment('qty', $qty);
            }

            return $transfer;
        });
    }

    public function updateTransfer(Transfer $transfer, array $data, iterable $products): Transfer
    {
        return DB::transaction(function () use ($transfer, $data, $products): \App\Models\Transfer {
            // Revert stock from old transfer
            foreach ($transfer->transferDetails as $detail) {
                // Re-increment the old source
                ProductWarehouse::query()->where('product_id', $detail->product_id)
                    ->where('warehouse_id', $transfer->from_warehouse_id)
                    ->increment('qty', $detail->quantity);

                // Decrement the old destination
                ProductWarehouse::query()->where('product_id', $detail->product_id)
                    ->where('warehouse_id', $transfer->to_warehouse_id)
                    ->decrement('qty', $detail->quantity);
            }

            // Delete old details
            $transfer->transferDetails()->delete();

            // Update transfer
            $transfer->update([
                'reference' => $data['reference'],
                'date' => $data['date'],
                'user_id' => $data['user_id'] ?? auth()->id(),
                'from_warehouse_id' => $data['from_warehouse_id'],
                'to_warehouse_id' => $data['to_warehouse_id'],
                'total_qty' => $data['total_qty'],
                'item' => count($products),
                'total_tax' => 0,
                'total_cost' => $data['total_cost'],
                'total_amount' => $data['total_amount'],
                'shipping' => $data['shipping_amount'] ?? 0,
                'document' => $data['document'] ?? null,
                'status' => $data['status'] ?? 1,
                'note' => $data['note'] ?? null,
            ]);

            // Apply new stock
            foreach ($products as $product) {
                $qty = $product['quantities'] ?? $product['quantity'] ?? 1;
                $productId = $product['id'] ?? $product['product_id'];

                TransferDetails::query()->create([
                    'transfer_id' => $transfer->id,
                    'product_id' => $productId,
                    'warehouse_id' => $data['to_warehouse_id'],
                    'quantity' => $qty,
                ]);

                // Decrement the source ProductWarehouse
                ProductWarehouse::query()->where('product_id', $productId)
                    ->where('warehouse_id', $data['from_warehouse_id'])
                    ->decrement('qty', $qty);

                // Increment the destination ProductWarehouse
                $destProductWarehouse = ProductWarehouse::query()->firstOrCreate([
                    'product_id' => $productId,
                    'warehouse_id' => $data['to_warehouse_id'],
                ], [
                    'price' => $product['price'] ?? 0,
                    'cost' => $product['cost'] ?? 0,
                    'qty' => 0,
                ]);

                $destProductWarehouse->increment('qty', $qty);
            }

            return $transfer;
        });
    }
}
