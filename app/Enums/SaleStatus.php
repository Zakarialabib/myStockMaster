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
}
