<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductStatus: string
{
    case Pending = '0';
    case Active = '1';
}
