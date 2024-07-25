<?php

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \App\Models\Role $resource
     * @return array
     */
    public function toArray($request)
    {
        $role = parent::toArray($request);

        return [
            'id' => (int) $this->resource->id,
            'name' => $this->resource->name,
            'guard_name' => $this->resource->guard_name,
            'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'), // Format date according to RFC3339 compatible format
            'updated_at' => $this->resource->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}

   