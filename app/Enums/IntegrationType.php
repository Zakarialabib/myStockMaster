<?php

declare(strict_types=1);

namespace App\Enums;

enum IntegrationType: string
{
    case CUSTOM = '0';

    case YOUCAN = '1';

    case WOOCOMMERCE = '2';

    case SHOPIFY = '3';
}
