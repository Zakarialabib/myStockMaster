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
        switch ($this) {
            case self::PENDING:
                return 'warning';
            case self::ORDERED:
                return 'primary';
            case self::COMPLETED:
                return 'success';
            case self::SHIPPED:
                return 'info';
            case self::RETURNED:
                return 'danger';
            case self::CANCELED:
                return 'secondary';
            default:
                return 'secondary';
        }
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
