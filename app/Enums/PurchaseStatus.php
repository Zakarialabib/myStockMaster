<?php

declare(strict_types=1);

namespace App\Enums;

enum PurchaseStatus: string
{
    case Pending = '0';

    case Ordered = '1';

    case Completed = '2';

    case Returned = '3';
}
