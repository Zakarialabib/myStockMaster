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

class RoleController extends Controller
{
    /**
     * Retrieve a list of roles with optional filters and pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->get('_end') !== null) {
            $limit = (int) ($request->input('_end') ?? 10);
            $offset = (int) ($request->input('_start') ?? 0);
            $order = (string) ($request->input('_order') ?? 'asc');
            $sort = (string) ($request->input('_sort') ?? 'id');

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
        $role = Role::create($storeRoleRequest->all());

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
        $role->update($updateRoleRequest->all());

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
