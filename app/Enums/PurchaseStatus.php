<?php

declare(strict_types=1);

namespace App\Enums;

enum PurchaseStatus: string
{
    case PENDING = '0';

    case ORDERED = '1';

    case COMPLETED = '2';

    case RETURNED = '3';

    case CANCELED = '4';

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    // loop through the values:

    // @foreach(App\Enums\PaymentStatus::values() as $key=>$value)
    //     <option value="{{ $key }}">{{ $value }}</option>
    // @endforeach
}
