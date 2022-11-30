<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

trait SaleScope
{

    public function scopeSalesTotal(Builder $builder, Carbon $date, int $dividedNumber = 100)
    {
        
        return $builder->whereDate('created_at', '>=', $date)->sum('total_amount') / $dividedNumber;
    }
}
