<?php

declare(strict_types=1);

namespace App\Enums;

enum SaleStatus: string
{
    case PENDING = '0';

    case ORDERED = '1';

    case COMPLETED = '2';

    case SHIPPED = '3';

    case RETURNED = '4';

    case CANCELED = '5';

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    // loop through the values:

    // @foreach(App\Enums\PaymentStatus::values() as $key=>$value)
    //     <option value="{{ $key }}">{{ $value }}</option>
    // @endforeach
}
