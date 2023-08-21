<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum MovementType: int
{
    case SALE = 0;

    case PURCHASE = 1;

    case SALERETURN = 2;

    case PURCHASERETURN = 3;

    case SALETRANSFER = 4;

    case PURCHASETRANSFER = 5;

    public function getName(): string
    {
        return __(Str::studly($this->name));
    }

    public function getValue()
    {
        return $this->value;
    }

    public static function getLabel($value)
    {
        foreach (self::cases() as $case) {
            if ($case->getValue() === $value) {
                return $case->getName();
            }
        }

        return null;
    }
}
