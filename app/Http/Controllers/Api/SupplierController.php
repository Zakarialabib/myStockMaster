<?php

declare(strict_types=1);

namespace app\Http\Controllers\Api;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Retrieve a list of suppliers with optional filters and pagination.
     */
    #[Get('/api/suppliers', name: 'api.suppliers.index')]
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

            // capture sort fields
            $sort_array = explode(',', $sort);

            if (count($sort_array) > 0) {
                // retireve ordered and limit suppliers list
                $suppliers = Supplier::whereRaw($where_raw)
                    // ->orderByRaw("COALESCE($sort)")
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            } else {
                // retireve ordered and limit suppliers list
                $suppliers = Supplier::orderBy($sort, $order)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            }
        } else {
            // retireve all suppliers
            $suppliers = Supplier::get();
        }

        return SupplierResource::collection($suppliers);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Post('/api/suppliers', name: 'api.suppliers.store')]
    #[Middleware('api')]
    public function store(StoreSupplierRequest $request): SupplierResource
    {
        $Supplier = Supplier::create($request->all());

        return new SupplierResource($Supplier);
    }

    /**
     * Display the specified resource.
     */
    #[Get('/api/suppliers/{id}', name: 'api.suppliers.show')]
    #[Middleware('api')]
    public function show(int $id): SupplierResource|JsonResponse
    {
        $Supplier = Supplier::find($id);

        if (is_null($Supplier)) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        return new SupplierResource($Supplier);
    }

    /**
     * Update the specified resource in storage.
     */
    #[Put('/api/suppliers/{id}', name: 'api.suppliers.update')]
    #[Middleware('api')]
    public function update(UpdateSupplierRequest $request, int $id): SupplierResource
    {
        $Supplier = Supplier::findOrFail($id);
        $Supplier->update($request->all());

        return new SupplierResource($Supplier);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        try {
            $Supplier = Supplier::findOrFail($id);
            $Supplier->delete();

            return $this->sendResponse($Supplier, 'Supplier deleted successfully');
        } catch (Exception $e) {
            DB::rollback();

            return $this->sendError($e->getMessage());
        }
    }
}
