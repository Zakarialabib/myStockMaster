<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum QuotationStatus: int
{
    case PENDING = 0;

    case SENT = 1;

    case ACCEPTED = 2;

    case EXPIRED = 3;

    case REJECTED = 4;

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
            self::SENT => 'info',
            self::ACCEPTED => 'success',
            self::EXPIRED => 'danger',
            self::REJECTED => 'alert',
            default => 'secondary',
        };
    }
}
