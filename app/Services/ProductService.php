<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\ProductWarehouse;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ProductService
{
    public function create(array $data): Product
    {
        $slug = Str::slug($data['name']);
        $code = $data['code'] ?? \Illuminate\Support\Facades\Date::now()->format('Y-m-d') . mt_rand(10000000, 99999999);

        $imageName = null;
        if (isset($data['image']) && is_object($data['image'])) {
            $imageName = Str::slug($data['name']) . '-' . $data['image']->extension();
            $data['image']->storeAs('products', $imageName, 'local_files');
        }

        $galleryData = null;
        if (isset($data['gallery']) && is_array($data['gallery']) && $data['gallery'] !== []) {
            $gallery = [];
            foreach ($data['gallery'] as $value) {
                if (is_object($value)) {
                    $gName = Str::slug($data['name']) . '-' . Str::random(5) . '.' . $value->extension();
                    $value->storeAs('products', $gName, 'local_files');
                    $gallery[] = $gName;
                } elseif (is_string($value)) {
                    $gallery[] = $value;
                }
            }

            $galleryData = json_encode($gallery);
        }

        $description = isset($data['note']) ? json_encode($data['note']) : null;
        if (isset($data['description'])) {
            $description = $data['description'];
        }

        $product = Product::query()->create([
            'name' => $data['name'],
            'code' => $code,
            'barcode_symbology' => $data['barcode_symbology'] ?? 'C128',
            'slug' => $slug,
            'unit' => $data['unit'] ?? 'pcs',
            'tax_amount' => $data['order_tax'] ?? $data['tax_amount'] ?? 0,
            'description' => $description,
            'tax_type' => $data['tax_type'] ?? 0,
            'category_id' => $data['category_id'],
            'brand_id' => $data['brand_id'] ?? null,
            'availability' => $data['availability'] ?? null,
            'seasonality' => $data['seasonality'] ?? null,
            'image' => $imageName,
            'gallery' => $galleryData,
            'embeded_video' => $data['embeded_video'] ?? null,
            'usage' => $data['usage'] ?? null,
            'featured' => $data['featured'] ?? false,
            'best' => $data['best'] ?? false,
            'hot' => $data['hot'] ?? false,
            'options' => $data['options'] ?? null,
        ]);

        if (isset($data['productWarehouse']) && isset($data['warehouse_id'])) {
            ProductWarehouse::query()->create([
                'product_id' => $product->id,
                'warehouse_id' => $data['warehouse_id'],
                'price' => $data['price'] ?? 0,
                'cost' => $data['cost'] ?? 0,
                'qty' => $data['productWarehouse']['qty'] ?? 0,
                'old_price' => $data['productWarehouse']['old_price'] ?? 0,
                'stock_alert' => $data['stock_alert'] ?? 0,
                'is_ecommerce' => $data['productWarehouse']['is_ecommerce'] ?? false,
            ]);
        }

        // Attributes are not defined on Product model
        // // if (isset($data['selectedAttributes'])) {
        //     foreach ($data['selectedAttributes'] as $id => $value) {
        //         $product->attributes()->updateExistingPivot($id, ['value' => $value]);
        //     }
        // }

        return $product;
    }

    public function update(Product $product, array $data): Product
    {
        $slug = $data['slug'] ?? $product->slug;
        if ($slug !== $product->slug || Str::slug($data['name']) !== $product->slug) {
            $slug = Str::slug($data['name']);
        }

        $code = $data['code'] ?? $product->code;
        if (blank($code)) {
            $code = \Illuminate\Support\Facades\Date::now()->format('Y-m-d') . mt_rand(10000000, 99999999);
        }

        $imageName = $product->image;
        if (isset($data['image']) && is_object($data['image']) && method_exists($data['image'], 'extension')) {
            $imageName = Str::slug($data['name']) . '-' . Str::random(5) . '.' . $data['image']->extension();
            $data['image']->storeAs('products', $imageName, 'local_files');
        } elseif (array_key_exists('image', $data) && $data['image'] === null) {
            $imageName = null;
        }

        $galleryData = $product->gallery;
        if (isset($data['gallery']) && is_array($data['gallery']) && $data['gallery'] !== []) {
            $gallery = [];
            foreach ($data['gallery'] as $value) {
                if (is_object($value)) {
                    $gName = Str::slug($data['name']) . '-' . Str::random(5) . '.' . $value->extension();
                    $value->storeAs('products', $gName, 'local_files');
                    $gallery[] = $gName;
                } else {
                    $gallery[] = $value;
                }
            }

            $galleryData = json_encode($gallery, JSON_THROW_ON_ERROR);
        } elseif (array_key_exists('gallery', $data) && blank($data['gallery'])) {
            $galleryData = null;
        }

        $description = isset($data['note']) ? json_encode($data['note']) : null;
        if (isset($data['description'])) {
            $description = $data['description'];
        }

        $product->update([
            'name' => $data['name'],
            'code' => $code,
            'barcode_symbology' => $data['barcode_symbology'] ?? 'C128',
            'slug' => $slug,
            'unit' => $data['unit'] ?? 'pcs',
            'tax_amount' => $data['order_tax'] ?? $data['tax_amount'] ?? 0,
            'description' => $description,
            'tax_type' => $data['tax_type'] ?? 0,
            'category_id' => $data['category_id'],
            'brand_id' => $data['brand_id'] ?? null,
            'availability' => $data['availability'] ?? null,
            'seasonality' => $data['seasonality'] ?? null,
            'image' => $imageName,
            'gallery' => $galleryData,
            'embeded_video' => $data['embeded_video'] ?? null,
            'usage' => $data['usage'] ?? null,
            'featured' => $data['featured'] ?? false,
            'best' => $data['best'] ?? false,
            'hot' => $data['hot'] ?? false,
            'options' => $data['options'] ?? null,
        ]);

        if (isset($data['productWarehouse']) && is_array($data['productWarehouse'])) {
            foreach ($data['productWarehouse'] as $warehouseId => $warehouse) {
                if (is_array($warehouse)) {
                    $product->warehouses()->updateExistingPivot($warehouseId, [
                        'price' => $warehouse['price'] ?? 0,
                        'qty' => $warehouse['qty'] ?? 0,
                        'cost' => $warehouse['cost'] ?? 0,
                        'old_price' => $warehouse['old_price'] ?? 0,
                        'stock_alert' => $warehouse['stock_alert'] ?? 0,
                        'is_ecommerce' => $warehouse['is_ecommerce'] ?? false,
                    ]);
                }
            }
        }

        // Attributes are not currently supported by the Product model relationship
        // foreach ($data['selectedAttributes'] ?? [] as $id => $value) {
        //     $product->attributes()->updateExistingPivot($id, ['value' => $value]);
        // }

        return $product;
    }
}
