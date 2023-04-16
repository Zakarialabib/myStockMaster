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
}
