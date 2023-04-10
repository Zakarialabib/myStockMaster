<?php

declare(strict_types=1);

namespace App\Http\Services\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FilterCountInterface
{
    /**
     * Apply the count filter to the query.
     *
     * @param  Builder  $query
     * @param  mixed  $startDate
     * @param  mixed  $endDate
     * @return Builder
     */
    public function filterCount($query, $startDate, $endDate);
}
