<?php

declare(strict_types=1);

namespace App\Enums;

enum Status: string
{
    case INACTIVE = '0';
    case ACTIVE = '1';
}
