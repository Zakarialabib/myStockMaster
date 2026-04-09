<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryService
{
    public function create(array $data): Category
    {
        if (isset($data['image']) && ! is_string($data['image']) && is_object($data['image']) && method_exists($data['image'], 'extension')) {
            if (method_exists($data['image'], 'isValid') && ! $data['image']->isValid()) {
                unset($data['image']);
            } elseif ($data['image']->getRealPath()) {
                $imageName = Str::slug($data['name']) . '-' . Str::random(5) . '.' . $data['image']->extension();
                $data['image']->storeAs('categories', $imageName, 'local_files');
                $data['image'] = $imageName;
            } else {
                unset($data['image']);
            }
        }

        return Category::query()->create($data);
    }

    public function update(Category $category, array $data): Category
    {
        if (isset($data['image']) && ! is_string($data['image']) && is_object($data['image']) && method_exists($data['image'], 'extension')) {
            if (method_exists($data['image'], 'isValid') && ! $data['image']->isValid()) {
                unset($data['image']);
            } elseif ($data['image']->getRealPath()) {
                $imageName = Str::slug($data['name']) . '-' . Str::random(5) . '.' . $data['image']->extension();
                $data['image']->storeAs('categories', $imageName, 'local_files');
                $data['image'] = $imageName;
            } else {
                unset($data['image']);
            }
        } elseif (array_key_exists('image', $data) && $data['image'] === null) {
            $data['image'] = null;
        }

        $category->update($data);

        return $category;
    }
}
