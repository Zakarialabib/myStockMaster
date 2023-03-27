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

 
}
