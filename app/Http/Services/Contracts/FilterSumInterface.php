<?php

namespace App\Http\Services\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FilterSumInterface
{
    /**
     * Apply the sum filter to the query.
     *
     * @param  Builder  $query
     * @param  mixed  $startDate
     * @param  mixed  $endDate
     * @return mixed
     */
    public function filterSum($query, $startDate, $endDate);
}
