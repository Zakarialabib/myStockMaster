<?php

declare(strict_types=1);

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait ProductScope
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Support\Carbon $date
     * @return mixed
     */
    public function scopeStockValue(Builder $builder, Carbon $date)
    {
        return $builder->whereDate('created_at', '>=', $date)->sum(DB::raw('quantity * cost'));
    }
}
