<?php

declare(strict_types=1);

namespace app\Http\Controllers\Api;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    /**
     * Retrieve a list of Warehouse with optional filters and pagination.
     */
    #[Get('/api/warehouses', name: 'api.warehouses.index')]
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
            // $brand_id = $request->get('brand_id') ? $request->get('brand_id')  : '';
            // if ($brand_id !== '') {
            //     $where_raw .= " AND (brand_id =  $brand_id)";
            // }

            // capture sort fields
            $sort_array = explode(',', $sort);

            if (count($sort_array) > 0) {
                // retireve ordered and limit Warehouses list
                $Warehouses = Warehouse::whereRaw($where_raw)
                     // ->orderByRaw("COALESCE($sort)")
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            } else {
                // retireve ordered and limit Warehouses list
                $Warehouses = Warehouse::orderBy($sort, $order)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            }
        } else {
            // retireve all Warehouses
            $Warehouses = Warehouse::get();
        }

        return WarehouseResource::collection($Warehouses);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Post('/api/warehouses', name: 'api.warehouses.store')]
    #[Middleware('api')]
    public function store(StoreWarehouseRequest $request): WarehouseResource
    {
        $Warehouse = Warehouse::create($request->all());

        return new WarehouseResource($Warehouse);
    }

    /**
     * Display the specified resource.
     */
    #[Get('/api/warehouses/{id}', name: 'api.warehouses.show')]
    #[Middleware('api')]
    public function show(int $id): WarehouseResource|JsonResponse
    {
        $Warehouse = Warehouse::find($id);

        if (is_null($Warehouse)) {
            return response()->json(['message' => 'Warehouse not found'], 404);
        }

        return new WarehouseResource($Warehouse);
    }

    /**
     * Update the specified resource in storage.
     */
    #[Put('/api/warehouses/{id}', name: 'api.warehouses.update')]
    #[Middleware('api')]
    public function update(UpdateWarehouseRequest $request, int $id): WarehouseResource
    {
        $Warehouse = Warehouse::findOrFail($id);
        $Warehouse->update($request->all());

        return new WarehouseResource($Warehouse);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        try {
            $Warehouse = Warehouse::findOrFail($id);
            $Warehouse->delete();

            return $this->sendResponse($Warehouse, 'Warehouse deleted successfully');
        } catch (Exception $e) {
            DB::rollback();

            return $this->sendError($e->getMessage());
        }
    }
}
