<?php

declare(strict_types=1);

namespace App\Enums;

enum ShippingStatus: string
{
    case  PENDING = '0';

    case  PREPARING = '1';

    case SUBMITTED = '2';

    case  SHIPPING = '3';

    case  DELIVERED = '4';

    case  CANCELLED = '5';

    case  FAILED = '6';

    public function label(): string
    {
        return match ($this) {
            static::PENDING   => __('Pending'),
            static::PREPARING => __('Processing'),
            static::SUBMITTED => __('Completed'),
            static::SHIPPING  => __('Shipped'),
            static::DELIVERED => __('Returned'),
            static::CANCELLED => __('Canceled'),
            static::FAILED    => __('Failed'),
        };
    }

    public function getBadgeType(): string
    {
        return match ($this) {
            self::PENDING   => 'secondary',
            self::PREPARING => 'info',
            self::SUBMITTED => 'success',
            self::SHIPPING  => 'primary',
            self::DELIVERED => 'success',
            self::CANCELLED => 'warning',
            self::FAILED    => 'danger',
            default         => 'secondary',
        };
    }
}
