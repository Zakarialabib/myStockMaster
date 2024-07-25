<?php

declare(strict_types=1);

namespace App\Traits;

use Carbon\Carbon;
use DateTimeInterface;

trait HasGlobalDate
{
    public function getDateAttribute($value): string
    {
        $date = $value instanceof DateTimeInterface ? $value : new Carbon($value);

        return $date->format('Y-m-d');
    }

    public function setDateAttribute($value): void
    {
        $this->attributes['date'] = Carbon::parse($value)->format('Y-m-d');
    }
}
