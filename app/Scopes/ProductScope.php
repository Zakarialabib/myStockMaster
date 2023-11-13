<?php

declare(strict_types=1);

namespace App\Scopes;

trait ProductScope
{
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeProductsByCategory($query, $category_id)
    {
        return $query->where('category_id', $category_id)->count();
    }
}
