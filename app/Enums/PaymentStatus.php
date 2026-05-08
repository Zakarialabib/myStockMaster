<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum PaymentStatus: int
{
    case PENDING = 0;
    case PAID = 1;
    case PARTIAL = 2;
    case DUE = 3;

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
            self::PARTIAL => 'info',
            self::PAID => 'success',
            self::DUE => 'danger',
            default => 'primary',
        };
    }
}
