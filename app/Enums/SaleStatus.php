<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum SaleStatus: int
{
    case PENDING = 0;

    case ORDERED = 1;

    case COMPLETED = 2;

    case SHIPPED = 3;

    case RETURNED = 4;

    case CANCELED = 5;

    public function getName(): string
    {
        return __(Str::studly($this->name));
    }

    public function getBadgeType(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::ORDERED => 'primary',
            self::COMPLETED => 'success',
            self::SHIPPED => 'info',
            self::RETURNED => 'danger',
            self::CANCELED => 'secondary',
        };
    }

    public function getValue()
    {
        return $this->value;
    }

    public static function getLabel($value): ?string
    {
        foreach (self::cases() as $case) {
            if ($case->getValue() === $value) {
                return $case->getName();
            }
        }

        return null;
    }
}
