<?php

declare(strict_types=1);

namespace app\Http\Controllers\Api;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Retrieve a list of Role with optional filters and pagination.
     */
    /**
     * Retrieve a list of roles with optional filters and pagination.
     */
    #[Get('/api/roles', name: 'api.roles.index')]
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

        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Post('/api/roles', name: 'api.roles.store')]
    #[Middleware('api')]
    public function store(StoreRoleRequest $request): RoleResource
    {
        $Role = Role::create($request->all());

        return new RoleResource($Role);
    }

    /**
     * Display the specified resource.
     */
    #[Get('/api/roles/{id}', name: 'api.roles.show')]
    #[Middleware('api')]
    public function show(int $id): RoleResource|JsonResponse
    {
        $Role = Role::find($id);

        if (is_null($Role)) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        return new RoleResource($Role);
    }

    /**
     * Update the specified resource in storage.
     */
    #[Put('/api/roles/{id}', name: 'api.roles.update')]
    #[Middleware('api')]
    public function update(UpdateRoleRequest $request, int $id): RoleResource
    {
        $Role = Role::findOrFail($id);
        $Role->update($request->all());

        return new RoleResource($Role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        try {
            $Role = Role::findOrFail($id);
            $Role->delete();

            return $this->sendResponse($Role, 'Role deleted successfully');
        } catch (Exception $e) {
            DB::rollback();

            return $this->sendError($e->getMessage());
        }
    }
}
