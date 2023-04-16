<?php

declare(strict_types=1);

namespace App\Enums;

enum QuotationStatus: string
{
    case PENDING = '0';

    case SENT = '1';

    case ACCEPTED = '2';

    case EXPIRED = '3';

    case REJECTED = '4';
}
