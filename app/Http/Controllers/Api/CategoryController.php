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
use App\Http\Controllers\Api\Concerns\SafeApiQuery;

class CategoryController extends Controller
{
    use SafeApiQuery;

    /**
     * Retrieve a list of categories with optional filters and pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {

        if ($request->get('_end') !== null) {
            $limit = (int) ($request->input('_end') ?? 10);
            $offset = (int) ($request->input('_start') ?? 0);

            $order = $this->safeOrderDirection($request);
            $sort = $this->safeSortColumn($request, ['id', 'code', 'name', 'status', 'created_at', 'updated_at']);

            // The previous implementation built a raw SQL fragment
            // ("WHERE 1=1 AND ...") via whereRaw(); that path is removed.
            $categories = Category::query()
                ->orderBy($sort, $order)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            // retireve all categories
            $categories = Category::query()->get();
        }

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $storeCategoryRequest): CategoryResource
    {
        $category = Category::query()->create($storeCategoryRequest->validated());

        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): CategoryResource|JsonResponse
    {
        $category = Category::query()->find($id);

        if (is_null($category)) {
            return new \Illuminate\Http\JsonResponse(['message' => 'Category not found'], 404);
        }

        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $updateCategoryRequest, int $id): CategoryResource
    {
        $category = Category::query()->findOrFail($id);
        $category->update($updateCategoryRequest->validated());

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $category = Category::query()->findOrFail($id);
        $category->delete();

        return new \Illuminate\Http\JsonResponse(['message' => 'Category deleted successfully']);
    }
}
