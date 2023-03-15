<?php

declare(strict_types=1);

namespace App\Enums;

enum IntegrationType: string
{
    case CUSTOM = '0';

    case YOUCAN = '1';

    case WOOCOMMERCE = '2';

    case SHOPIFY = '3';

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    // loop through the values:

    // @foreach(App\Enums\IntegrationType::values() as $key=>$value)
    //     <option value="{{ $key }}">{{ $value }}</option>
    // @endforeach
}
