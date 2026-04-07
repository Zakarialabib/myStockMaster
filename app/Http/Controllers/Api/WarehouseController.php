<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WarehouseController extends Controller
{
    /**
     * Retrieve a list of Warehouse with optional filters and pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->get('_end') !== null) {
            $limit = (int) ($request->input('_end') ?? 10);
            $offset = (int) ($request->input('_start') ?? 0);
            $order = (string) ($request->input('_order') ?? 'asc');
            $sort = (string) ($request->input('_sort') ?? 'id');

            $warehouses = Warehouse::query()
                ->orderBy($sort, $order)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $warehouses = Warehouse::query()->get();
        }

        return WarehouseResource::collection($warehouses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWarehouseRequest $storeWarehouseRequest): WarehouseResource
    {
        $warehouse = Warehouse::query()->create($storeWarehouseRequest->all());

        return new WarehouseResource($warehouse);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): WarehouseResource|JsonResponse
    {
        $warehouse = Warehouse::query()->find($id);

        if ($warehouse === null) {
            return new \Illuminate\Http\JsonResponse(['message' => 'Warehouse not found'], 404);
        }

        return new WarehouseResource($warehouse);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWarehouseRequest $updateWarehouseRequest, int $id): WarehouseResource
    {
        $warehouse = Warehouse::query()->findOrFail($id);
        $warehouse->update($updateWarehouseRequest->all());

        return new WarehouseResource($warehouse);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $warehouse = Warehouse::query()->findOrFail($id);
        $warehouse->delete();

        return new \Illuminate\Http\JsonResponse(['message' => 'Warehouse deleted successfully']);
    }
}
