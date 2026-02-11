<?php

declare(strict_types=1);

namespace App\Exceptions\Cart;

class InvalidQuantityException extends CartException
{
    public function __construct(int $quantity, string $reason = 'Invalid quantity provided', array $context = [])
    {
        $message = "Invalid quantity {$quantity}: {$reason}";
        parent::__construct($message, 400, null, array_merge($context, ['quantity' => $quantity]));
        $this->setErrorCode('INVALID_QUANTITY');
    }
}
