<?php

declare(strict_types=1);

namespace App\Enums;

enum SaleStatus: string
{
    case Pending = '0';

    case Ordered = '1';

    case Completed = '2';

    case Shipped = '3';

    case Returned = '4';

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    // loop through the values:

    // @foreach(App\Enums\PaymentStatus::values() as $key=>$value)
    //     <option value="{{ $key }}">{{ $value }}</option>
    // @endforeach
}
