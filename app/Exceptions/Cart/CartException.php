<?php

declare(strict_types=1);

namespace App\Exceptions\Cart;

use Exception;

class CartException extends Exception
{
    protected $errorCode;

    public function __construct(string $message = '', int $code = 0, ?Exception $previous = null, protected array $context = [])
    {
        parent::__construct($message, $code, $previous);
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function setErrorCode(string $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }
}
