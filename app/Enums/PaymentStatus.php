<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = '0';

    case Paid = '1';

    case Partial = '2';

    case Due = '3';
}
