<?php

declare(strict_types=1);

namespace App\Exceptions\Cart;

class ProductNotAvailableException extends CartException
{
    public function __construct(int $productId, string $reason = 'Product is not available', array $context = [])
    {
        $message = "Product ID {$productId} is not available: {$reason}";
        parent::__construct($message, 404, null, array_merge($context, ['product_id' => $productId]));
        $this->setErrorCode('PRODUCT_NOT_AVAILABLE');
    }
}
