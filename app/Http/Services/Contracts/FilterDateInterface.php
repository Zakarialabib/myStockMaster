<?php

declare(strict_types=1);

namespace App\Http\Services\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FilterDateInterface
{
    /**
     * Apply the date filter to the query.
     *
     * @param  Builder  $query
     * @param  mixed  $startDate
     * @param  mixed  $endDate
     * @return Builder
     */
    public function filterDate($query, $startDate, $endDate);
}
