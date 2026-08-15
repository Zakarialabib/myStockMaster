<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Concerns;

use Illuminate\Http\Request;

/**
 * Helpers to safely build ORDER BY clauses from user input and to
 * neutralise SQL injection through raw query string parameters.
 */
trait SafeApiQuery
{
    /**
     * Return a whitelisted column name for ORDER BY. Any value that is not
     * explicitly allowed falls back to the safe default, preventing column
     * injection (e.g. "_sort=id; DROP TABLE users").
     */
    protected function safeSortColumn(Request $request, array $allowed, string $default = 'id'): string
    {
        $sort = (string) $request->input('_sort', $default);

        return in_array($sort, $allowed, true) ? $sort : $default;
    }

    /**
     * Return a normalised, safe ORDER BY direction (ASC or DESC). Any value
     * other than "DESC" (case-insensitive) is treated as ASC.
     */
    protected function safeOrderDirection(Request $request, string $default = 'asc'): string
    {
        $order = strtoupper((string) $request->input('_order', $default));

        return $order === 'DESC' ? 'DESC' : 'ASC';
    }
}
