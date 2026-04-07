<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum PurchaseStatus: int
{
    case PENDING = 0;

    case ORDERED = 1;

    case COMPLETED = 2;

    case RETURNED = 3;

    case CANCELED = 4;

    public function getName(): string
    {
        return __(Str::studly($this->name));
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

    public function getBadgeType(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::ORDERED => 'primary',
            self::COMPLETED => 'success',
            self::RETURNED => 'info',
            self::CANCELED => 'danger',
            default => 'dark',
        };
    }
}
