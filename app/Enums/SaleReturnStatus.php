<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum SaleReturnStatus: int
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
            case self::ORDERED:
                return 'info';
            case self::COMPLETED:
                return 'success';
            case self::SHIPPED:
                return 'primary';
            case self::RETURNED:
                return 'alert';
            case self::CANCELED:
                return 'danger';
            default:
                return 'secondary';
        }
    }
}
