<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait ProductScope
{

    public function scopeStockValue(Builder $builder, Carbon $date)
    {
        
        return $builder->whereDate('created_at', '>=', $date)->sum(DB::raw('quantity * cost'));
    }
}
