<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = '0';

    case Paid = '1';

    case Partial = '2';

    case Due = '3';

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    // loop through the values:

    // @foreach(App\Enums\PaymentStatus::values() as $key=>$value)
    //     <option value="{{ $key }}">{{ $value }}</option>
    // @endforeach
}
