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
use App\Http\Controllers\Api\Concerns\SafeApiQuery;

class ProductController extends Controller
{
    use SafeApiQuery;

    /**
     * Retrieve a list of products with optional filters and pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {

        if ($request->get('_end') !== null) {
            $limit = (int) ($request->input('_end') ?? 10);
            $offset = (int) ($request->input('_start') ?? 0);

            $order = $this->safeOrderDirection($request);
            $sort = $this->safeSortColumn($request, ['id', 'name', 'code', 'status', 'featured', 'created_at', 'updated_at']);

            $query = Product::query()->with(['category', 'brand']);

            // Capture brand_id filter — cast to int so injection is impossible.
            if ($request->filled('brand_id')) {
                $query->where('brand_id', $request->integer('brand_id'));
            }

            $products = $query
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
    public function store(StoreProductRequest $storeProductRequest): ProductResource
    {
        $product = Product::query()->create($storeProductRequest->validated());

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): ProductResource|JsonResponse
    {
        $product = Product::query()->find($id);

        if (is_null($product)) {
            return new \Illuminate\Http\JsonResponse(['message' => 'Product not found'], 404);
        }

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $updateProductRequest, int $id): ProductResource
    {
        $product = Product::query()->findOrFail($id);
        $product->update($updateProductRequest->validated());

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $product = Product::query()->findOrFail($id);
        $product->delete();

        return new \Illuminate\Http\JsonResponse(['message' => 'Product deleted successfully']);
    }
}
