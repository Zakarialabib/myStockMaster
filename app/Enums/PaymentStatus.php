<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = '0';

    case PAID = '1';

    case PARTIAL = '2';

    case DUE = '3';
}
