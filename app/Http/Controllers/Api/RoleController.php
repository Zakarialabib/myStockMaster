<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Api\Concerns\SafeApiQuery;

class RoleController extends Controller
{
    use SafeApiQuery;

    /**
     * Retrieve a list of roles with optional filters and pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->get('_end') !== null) {
            $limit = (int) ($request->input('_end') ?? 10);
            $offset = (int) ($request->input('_start') ?? 0);
            $order = $this->safeOrderDirection($request);
            $sort = $this->safeSortColumn($request, ['id', 'name', 'created_at', 'updated_at']);

            $roles = Role::query()
                ->orderBy($sort, $order)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $roles = Role::query()->get();
        }

        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $storeRoleRequest): RoleResource
    {
        $role = Role::create($storeRoleRequest->validated());

        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): RoleResource|JsonResponse
    {
        $role = Role::query()->find($id);

        if ($role === null) {
            return new \Illuminate\Http\JsonResponse(['message' => 'Role not found'], 404);
        }

        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $updateRoleRequest, int $id): RoleResource
    {
        $role = Role::query()->findOrFail($id);
        $role->update($updateRoleRequest->validated());

        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $role = Role::query()->findOrFail($id);
        $role->delete();

        return new \Illuminate\Http\JsonResponse(['message' => 'Role deleted successfully']);
    }
}
