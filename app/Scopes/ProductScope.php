<?php

declare(strict_types=1);

namespace App\Scopes;

trait ProductScope
{
    protected function scopeActive($query)
    {
        return $query->where('status', true);
    }

    protected function scopeProductsByCategory($query, $category_id)
    {
        return $query->where('category_id', $category_id)->count();
    }
}
