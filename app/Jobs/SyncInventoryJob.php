<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\MovementType;
use App\Models\Movement;
use App\Models\Product;
use App\Models\ProductWarehouse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncInventoryJob implements ShouldQueue
{
    use \Illuminate\Foundation\Queue\Queueable;

    public function __construct(
        public array $items,
        public int|string $warehouseId,
        public int|string $userId,
        public string $type = 'sale' // 'sale' or 'purchase'
    ) {}

    public function handle(): void
    {
        foreach ($this->items as $item) {
            $productId = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $unitPrice = $item['unit_price'] ?? $item['price'];

            $product = Product::query()->findOrFail($productId);

            $productWarehouse = ProductWarehouse::query()->firstOrNew([
                'product_id' => $product->id,
                'warehouse_id' => $this->warehouseId,
            ], [
                'price' => $price / 100,
                'cost' => $unitPrice / 100,
                'qty' => 0,
            ]);

            if ($this->type === 'sale') {
                $productWarehouse->qty -= $quantity;
            } else {
                $newQuantity = $productWarehouse->qty + $quantity;
                $newCost = (($productWarehouse->cost * $productWarehouse->qty) + ($unitPrice / 100 * $quantity)) / max(1, $newQuantity);

                $productWarehouse->qty = $newQuantity;
                $productWarehouse->cost = $newCost;
            }

            $productWarehouse->save();

            Movement::query()->create([
                'type' => $this->type === 'sale' ? MovementType::SALE : MovementType::PURCHASE,
                'quantity' => $quantity,
                'price' => $price, // already in cents based on how it's passed
                'date' => date('Y-m-d'),
                'movable_type' => $product::class,
                'movable_id' => $product->id,
                'user_id' => $this->userId,
            ]);
        }
    }
}
