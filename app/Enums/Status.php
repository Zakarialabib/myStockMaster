<?php

declare(strict_types=1);

namespace App\Enums;

enum Status: string
{
    case INACTIVE = '0';
    case ACTIVE = '1';

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
