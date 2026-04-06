<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryService
{
    public function create(array $data): Category
    {
        if (isset($data['image']) && ! is_string($data['image'])) {
            $imageName = Str::slug($data['name']) . '-' . Str::random(3) . '.' . $data['image']->extension();
            $data['image']->storeAs('categories', $imageName);
            $data['image'] = $imageName;
        }

        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        if (isset($data['image']) && ! is_string($data['image'])) {
            $imageName = Str::slug($data['name']) . '-' . Str::random(3) . '.' . $data['image']->extension();
            $data['image']->storeAs('categories', $imageName);
            $data['image'] = $imageName;
        }

        $category->update($data);

        return $category;
    }
}
