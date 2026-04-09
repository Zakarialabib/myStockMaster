<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Str;

class BrandService
{
    public function create(array $data): Brand
    {
        if (isset($data['image']) && ! is_string($data['image']) && is_object($data['image']) && method_exists($data['image'], 'extension')) {
            if (method_exists($data['image'], 'isValid') && !$data['image']->isValid()) {
                unset($data['image']);
            } elseif ($data['image']->getRealPath()) {
                $imageName = Str::slug($data['name']) . '-' . Str::random(5) . '.' . $data['image']->extension();
                $data['image']->storeAs('brands', $imageName, 'local_files');
                $data['image'] = $imageName;
            } else {
                unset($data['image']);
            }
        }

        return Brand::query()->create($data);
    }

    public function update(Brand $brand, array $data): Brand
    {
        if (isset($data['image']) && ! is_string($data['image']) && is_object($data['image']) && method_exists($data['image'], 'extension')) {
            if (method_exists($data['image'], 'isValid') && !$data['image']->isValid()) {
                unset($data['image']);
            } elseif ($data['image']->getRealPath()) {
                $imageName = Str::slug($data['name']) . '-' . Str::random(5) . '.' . $data['image']->extension();
                $data['image']->storeAs('brands', $imageName, 'local_files');
                $data['image'] = $imageName;
            } else {
                unset($data['image']);
            }
        } elseif (array_key_exists('image', $data) && $data['image'] === null) {
            $data['image'] = null;
        }

        $brand->update($data);

        return $brand;
    }
}
