<?php

declare(strict_types=1);

namespace App\Exceptions\Cart;

class InsufficientStockException extends CartException
{
    public function __construct(int $productId, int $warehouseId, int $requestedQty, int $availableQty, array $context = [])
    {
        $message = "Insufficient stock for product ID {$productId} in warehouse {$warehouseId}. Requested: {$requestedQty}, Available: {$availableQty}";
        parent::__construct($message, 400, null, array_merge($context, [
            'product_id'         => $productId,
            'warehouse_id'       => $warehouseId,
            'requested_quantity' => $requestedQty,
            'available_quantity' => $availableQty,
        ]));
        $this->setErrorCode('INSUFFICIENT_STOCK');
    }
}
