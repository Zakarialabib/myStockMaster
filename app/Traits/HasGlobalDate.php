<?php

declare(strict_types=1);

namespace App\Traits;

use Carbon\Carbon;
use DateTimeInterface;

trait HasGlobalDate
{
    protected function getDateAttribute($value): string
    {
        $date = $value instanceof DateTimeInterface ? $value : new Carbon($value);

        return $date->format('Y-m-d');
    }

    protected function setDateAttribute(\DateTimeInterface|\Carbon\WeekDay|\Carbon\Month|string|int|float|null $value): void
    {
        $this->attributes['date'] = \Illuminate\Support\Facades\Date::parse($value)->format('Y-m-d');
    }
}
