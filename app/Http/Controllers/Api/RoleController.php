<?php

namespace app\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends BaseController
{
    /**
     * Retrieve a list of Role with optional filters and pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    /**
     * Retrieve a list of roles with optional filters and pagination.
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

                //capture brand_id filter
                // $brand_id = $request->get('brand_id') ? $request->get('brand_id')  : '';
                // if ($brand_id !== '') {
                //     $where_raw .= " AND (brand_id =  $brand_id)";
                // }
                //capture sort fields 
                $sort_array = explode(',', $sort);
                if (count($sort_array) > 0) {
                    // retireve ordered and limit roles list
                    $roles = Role::whereRaw($where_raw)
                        // ->orderByRaw("COALESCE($sort)")
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
                } else {
                    // retireve ordered and limit roles list
                    $roles = Role::orderBy($sort, $order)
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
                }
            } else {
                // retireve all roles
                $roles = Role::get();
            }
            return $this->sendResponse($roles, 'Role List');
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
            $Role = Role::create($input);
            DB::commit();
            return $this->sendResponse($Role, 'Role created successfully');
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
            $Role = Role::find($id);
            if (is_null($Role)) {
                return $this->sendError('Role not found');
            } else {
                return $this->sendResponse($Role, 'Role retrieved successfully');
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
        $Role = Role::findOrFail($id);
        $Role->update($request->all());

        return new RoleResource($Role);
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
            $Role = Role::findOrFail($id);
            $Role->delete();
            return $this->sendResponse($Role, 'Role deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }
}
