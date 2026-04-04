<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Retrieve a list of products with optional filters and pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {

        if ($request->get('_end') !== null) {
            $limit = $request->get('_end') ? $request->get('_end') : 10;
            $offset = $request->get('_start') ? $request->get('_start') : 0;

            $order = $request->get('_order') ? $request->get('_order') : 'asc';
            $sort = $request->get('_sort') ? $request->get('_sort') : 'id';
            // Filters
            $where_raw = ' 1=1 ';

            // capture brand_id filter
            $brand_id = $request->get('brand_id') ? $request->get('brand_id') : '';

            if ($brand_id !== '') {
                $where_raw .= " AND (brand_id =  $brand_id)";
            }
            $products = Product::with(['category', 'brand'])
                ->whereRaw($where_raw)
                ->orderBy($sort, $order)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            // retireve all products
            $products = Product::with(['category', 'brand'])->get();
        }

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): ProductResource
    {
        $product = Product::create($request->all());

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): ProductResource|JsonResponse
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, int $id): ProductResource
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
