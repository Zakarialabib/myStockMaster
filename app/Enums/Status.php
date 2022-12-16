<?php

declare(strict_types=1);

namespace App\Enums;

enum Status: string
{
    case Inactive = '0';
    case Active = '1';
}
