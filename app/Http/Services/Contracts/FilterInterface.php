<?php

declare(strict_types=1);

namespace App\Http\Services\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FilterInterface
{
    /**
     * Apply the filter to the query.
     *
     * @param  Builder  $query
     * @param  mixed|null  $value
     * @return Builder
     */
    public function filter($query, $value = null);
}
