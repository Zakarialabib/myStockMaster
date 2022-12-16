<?php

declare(strict_types=1);

namespace App\Enums;

enum PurchaseReturnStatus: string
{
    case Pending = '0';

    case Ordered = '1';

    case Completed = '2';

    case Returned = '3';
}
