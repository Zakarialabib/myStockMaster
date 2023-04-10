<?php

declare(strict_types=1);

namespace App\Domain\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Services\Contracts\FilterDateInterface;
use App\Http\Services\Contracts\FilterSumInterface;

class DateFilter implements FilterDateInterface, FilterSumInterface
{
    /**
     * Apply the date filter to the query.
     *
     * @param  Builder  $query
     * @param  mixed  $startDate
     * @param  mixed  $endDate
     * @return Builder
     */
    public function filterDate($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Apply the sum filter to the query.
     *
     * @param  Builder  $query
     * @param  mixed  $startDate
     * @param  mixed  $endDate
     * @return mixed
     */
    public function filterSum($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
    }
}
