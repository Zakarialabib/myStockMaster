<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum BackupSchedule: int
{
    case DAILY = 0;

    case WEEKLY = 1;

    case MONTHLY = 2;

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
