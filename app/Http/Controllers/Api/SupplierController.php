<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SupplierController extends Controller
{
    /**
     * Retrieve a list of suppliers with optional filters and pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->get('_end') !== null) {
            $limit = (int) ($request->input('_end') ?? 10);
            $offset = (int) ($request->input('_start') ?? 0);
            $order = (string) ($request->input('_order') ?? 'asc');
            $sort = (string) ($request->input('_sort') ?? 'id');

            $suppliers = Supplier::query()
                ->orderBy($sort, $order)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $suppliers = Supplier::query()->get();
        }

        return SupplierResource::collection($suppliers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request): SupplierResource
    {
        $supplier = Supplier::create($request->all());

        return new SupplierResource($supplier);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): SupplierResource|JsonResponse
    {
        $supplier = Supplier::find($id);

        if ($supplier === null) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        return new SupplierResource($supplier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, int $id): SupplierResource
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());

        return new SupplierResource($supplier);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return response()->json(['message' => 'Supplier deleted successfully']);
    }
}
