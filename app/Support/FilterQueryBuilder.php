<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FilterQueryBuilder
{
    /** @var mixed */
    protected $model;

    /** @var mixed */
    protected $table;

    /**
     * @param mixed $query
     * @param mixed $data
     *
     * @return mixed
     */
    public function apply($query, $data)
    {
        $this->model = $query->getModel();
        $this->table = $this->model->getTable();

        if (isset($data['f'])) {
            foreach ($data['f'] as $filter) {
                $filter['match'] = $data['filter_match'] ?? 'and';
                $this->makeFilter($query, $filter);
            }
        }

        $this->makeOrder($query, $data);

        return $query;
    }

    /**
     * @param mixed $filter
     * @param mixed $query
     *
     * @return mixed
     */
    public function contains($filter, $query)
    {
        $filter['query_1'] = addslashes($filter['query_1']);

        return $query->where($filter['column'], 'like', '%'.$filter['query_1'].'%', $filter['match']);
    }

    /**
     * @param mixed $query
     * @param mixed $data
     *
     * @return mixed
     */
    protected function makeOrder($query, $data)
    {
        if ($this->isNestedColumn($data['order_column'])) {
            [$relationship, $column] = explode('.', $data['order_column']);
            $callable = Str::camel($relationship);
            $belongs = $this->model->{$callable}(
            );
            $relatedModel = $belongs->getModel();
            $relatedTable = $relatedModel->getTable();
            $as = "prefix_{$relatedTable}";

            if ( ! $belongs instanceof BelongsTo) {
                return;
            }

            $query->leftJoin(
                "{$relatedTable} as {$as}",
                "{$as}.id",
                '=',
                "{$this->table}.{$relationship}_id"
            );

            $data['order_column'] = "{$as}.{$column}";
        }

        $query
            ->orderBy($data['order_column'], $data['order_direction'])
            ->select("{$this->table}.*");
    }

    /**
     * @param mixed $filter
     * @param mixed $query
     *
     * @return mixed
     */
    protected function makeFilter($query, $filter)
    {
        if ($this->isNestedColumn($filter['column'])) {
            [$relation, $filter['column']] = explode('.', $filter['column']);
            $callable = Str::camel($relation);
            $filter['match'] = 'and';

            // Use the `remember` method to cache the query.
            $query->orWhereHas(Str::camel($callable), function ($q) use ($filter) {
                $this->{Str::camel($filter['operator'])}(
                    $filter,
                    $q
                );
            })->remember(10); // Cache the result for 10 minutes.
        } else {
            $filter['column'] = "{$this->table}.{$filter['column']}";
            $this->{Str::camel($filter['operator'])}(
                $filter,
                $query
            );
        }
    }

    /**
     * @param mixed $column
     *
     * @return bool
     */
    protected function isNestedColumn($column)
    {
        return strpos($column, '.') !== false;
    }
}
