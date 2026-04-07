<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Validation\ValidationException;

trait HasAdvancedFilter
{
    /**
     * @param mixed $query
     * @param mixed $data
     *
     * @return mixed
     */
    protected function scopeAdvancedFilter($query, $data)
    {
        return $this->processQuery($query, $data);
    }

    /**
     * @param mixed $query
     * @param mixed $data
     *
     * @return mixed
     */
    public function processQuery($query, $data)
    {
        $data = $this->processGlobalSearch($data);

        $validator = validator()->make($data, [
            's' => 'sometimes|nullable|string',
            'order_column' => 'sometimes|required|in:' . $this->orderableColumns(),
            'order_direction' => 'sometimes|required|in:asc,desc',
            // 'limit'           => 'sometimes|required|integer|min:1',

            // advanced filter
            'filter_match' => 'sometimes|required|in:and,or',
            'f' => 'sometimes|required|array',
            'f.*.column' => 'required|in:' . $this->whiteListColumns(),
            'f.*.operator' => 'required_with:f.*.column|in:' . $this->allowedOperators(),
            'f.*.query_1' => 'required',
            'f.*.query_2' => 'required_if:f.*.operator,between,not_between',
        ]);

        throw_if($validator->fails(), ValidationException::class, $validator);

        $data = $validator->validated();

        return (new FilterQueryBuilder)->apply($query, $data);
    }

    protected function orderableColumns(): string
    {
        return implode(',', $this->orderable);
    }

    protected function whiteListColumns(): string
    {
        return implode(',', $this->filterable);
    }

    protected function allowedOperators(): string
    {
        return implode(',', [
            'contains',
        ]);
    }

    /**
     * @param mixed $data
     */
    protected function processGlobalSearch(array $data): array
    {
        if (isset($data['f']) || ! isset($data['s'])) {
            return $data;
        }

        $data['filter_match'] = 'or';

        $data['f'] = array_map(fn($column) => [
            'column' => $column,
            'operator' => 'contains',
            'query_1' => $data['s'],
        ], $this->filterable);

        return $data;
    }
}
