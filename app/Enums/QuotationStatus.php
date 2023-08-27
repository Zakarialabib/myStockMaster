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

    public static function getLabel($value)
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
        switch ($this) {
            case self::PENDING:
                return 'warning';
            case self::SENT:
                return 'info';
            case self::ACCEPTED:
                return 'success';
            case self::EXPIRED:
                return 'danger';
            case self::REJECTED:
                return 'alert';
            default:
                return 'secondary';
        }
    }
}
