<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Role;
use App\Models\Warehouse;
use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \App\Models\User $resource
     * @return array
     */
    public function toArray($resource): array
    {
        return [
            'id'               => $resource->id,
            'uuid'             => $resource->uuid,
            'name'             => $resource->name,
            'email'            => $resource->email,
            'avatar'           => $resource->avatar,
            'phone'            => $resource->phone,
            'role'             => new RoleResource(Role::find($resource->role_id)), // Relationship with Role resource
            'defaultWarehouse' => new WarehouseResource(Warehouse::find($resource->default_warehouse_id)), // Nested relation for default warehouse
            'createdAt'        => $resource->created_at->format('Y-m-d H:i:s'), // Custom formatting of created_at
            'updatedAt'        => $resource->updated_at->format('Y-m-d H:i:s'), // Custom formatting of updated_at
            'isAllWarehouses'  => $resource->isAllWarehouses,
            // 'deletedAt' => $resource->deleted_at ? $resource->deleted_at->format('Y-m-d H:i:s') // Show deleted time if available
        ];
    }
}
