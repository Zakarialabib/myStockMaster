<?php

namespace app\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends BaseController
{
     /**
     * Retrieve a list of suppliers with optional filters and pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function index(Request $request)
    {
        try {
            if ($request->get('_end') !== null) {
                //
                $limit = $request->get('_end') ? $request->get('_end') : 10;
                $offset = $request->get('_start') ? $request->get('_start') : 0;
                //
                $order = $request->get('_order') ? $request->get('_order') : 'asc';
                $sort = $request->get('_sort') ?  $request->get('_sort') : 'id';
                //Filters
                $where_raw = ' 1=1 ';

                //capture sort fields 
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
            return $this->sendResponse($suppliers, 'Supplier List');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $Supplier = Supplier::create($input);
            DB::commit();
            return $this->sendResponse($Supplier, 'Supplier created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     */
    public function show($id)
    {
        try {
            $Supplier = Supplier::find($id);
            if (is_null($Supplier)) {
                return $this->sendError('Supplier not found');
            } else {
                return $this->sendResponse($Supplier, 'Supplier retrieved successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     */
    public function update(Request $request, $id)
    {
        $Supplier = Supplier::findOrFail($id);
        $Supplier->update($request->all());

        return new SupplierResource($Supplier);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     */
    public function destroy($id)
    {
        try {
            $Supplier = Supplier::findOrFail($id);
            $Supplier->delete();
            return $this->sendResponse($Supplier, 'Supplier deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }
}
