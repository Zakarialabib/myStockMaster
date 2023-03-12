<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductStatus: string
{
    case INACTIVE = '0';
    case ACTIVE = '1';

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    // loop through the values:

    // @foreach(App\Enums\PaymentStatus::values() as $key=>$value)
    //     <option value="{{ $key }}">{{ $value }}</option>
    // @endforeach
}
