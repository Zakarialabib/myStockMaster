<?php

declare(strict_types=1);

namespace App\Enums;

enum TaxType: string
{
    case KG = 'kg';

    case PIECE = 'pcs';

    case METRE = 'm';

    case Gram = 'gr';

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    // loop through the values:

    // @foreach(App\Enums\PaymentStatus::values() as $key=>$value)
    //     <option value="{{ $key }}">{{ $value }}</option>
    // @endforeach
}
