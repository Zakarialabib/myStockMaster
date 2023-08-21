<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum IntegrationType: int
{
    case CUSTOM = 0;

    case YOUCAN = 1;

    case WOOCOMMERCE = 2;

    case SHOPIFY = 3;

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
