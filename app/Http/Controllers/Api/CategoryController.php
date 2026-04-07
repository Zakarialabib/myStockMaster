<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    /**
     * Retrieve a list of categories with optional filters and pagination.
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
            // $brand_id = $request->get('brand_id') ? $request->get('brand_id')  : '';
            // if ($brand_id !== '') {
            //     $where_raw .= " AND (brand_id =  $brand_id)";
            // }
            $categories = Category::whereRaw($where_raw)
                ->orderBy($sort, $order)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            // retireve all categories
            $categories = Category::get();
        }

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $category = Category::create($request->all());

        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): CategoryResource|JsonResponse
    {
        $category = Category::find($id);

        if (is_null($category)) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, int $id): CategoryResource
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
