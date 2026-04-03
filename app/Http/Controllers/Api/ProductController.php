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
use Illuminate\Routing\Attributes\Delete;
use Illuminate\Routing\Attributes\Get;
use Illuminate\Routing\Attributes\Middleware;
use Illuminate\Routing\Attributes\Post;
use Illuminate\Routing\Attributes\Put;

class ProductController extends Controller
{
    /**
     * Retrieve a list of products with optional filters and pagination.
     */
    #[Get('/api/products', name: 'api.products.index')]
    #[Middleware('api')]
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
            // capture sort fields
            $sort_array = explode(',', $sort);

            if (count($sort_array) > 0) {
                // retireve ordered and limit products list
                $products = Product::with(['category', 'brand'])
                    ->whereRaw($where_raw)
                    // ->orderByRaw("COALESCE($sort)")
                    // ->offset($offset)
                    // ->limit($limit)
                    ->get();
            } else {
                // retireve ordered and limit products list
                $products = Product::with(['category', 'brand'])
                    ->orderBy($sort, $order)
                    // ->offset($offset)
                    // ->limit($limit)
                    ->get();
            }
        } else {
            // retireve all products
            $products = Product::with(['category', 'brand'])->get();
        }

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Post('/api/products', name: 'api.products.store')]
    #[Middleware('api')]
    public function store(StoreProductRequest $request): ProductResource
    {
        $product = Product::create($request->all());

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    #[Get('/api/products/{id}', name: 'api.products.show')]
    #[Middleware('api')]
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
    #[Put('/api/products/{id}', name: 'api.products.update')]
    #[Middleware('api')]
    public function update(UpdateProductRequest $request, int $id): ProductResource
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[Delete('/api/products/{id}', name: 'api.products.destroy')]
    #[Middleware('api')]
    public function destroy(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
