<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Str;

class BrandService
{
    public function create(array $data): Brand
    {
        if (isset($data['image']) && ! is_string($data['image'])) {
            $imageName = Str::slug($data['name']) . '-' . Str::random(5) . '.' . $data['image']->extension();
            $data['image']->storeAs('brands', $imageName, 'local_files');
            $data['image'] = $imageName;
        }

        return Brand::query()->create($data);
    }

    public function update(Brand $brand, array $data): Brand
    {
        if (isset($data['image']) && ! is_string($data['image'])) {
            $imageName = Str::slug($data['name']) . '-' . Str::random(5) . '.' . $data['image']->extension();
            $data['image']->storeAs('brands', $imageName, 'local_files');
            $data['image'] = $imageName;
        }

        $brand->update($data);

        return $brand;
    }
}
