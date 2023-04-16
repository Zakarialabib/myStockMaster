<?php

declare(strict_types=1);

namespace App\Enums;

enum MovementType: string
{
    case SALE = '0';

    case PURCHASE = '1';

    case SALERETURN = '2';

    case PURCHASERETURN = '3';

    case SALETRANSFER = '4';

    case PURCHASETRANSFER = '5';
}
