<?php

declare(strict_types=1);

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait SaleScope
{
    protected function scopeSalesTotal(Builder $builder, Carbon $date, int $dividedNumber = 100): int|float
    {
        return $builder->whereDate('created_at', '>=', $date)->sum('total_amount') / $dividedNumber;
    }
}
