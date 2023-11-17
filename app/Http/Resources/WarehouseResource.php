<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \App\Models\Warehouse $resource
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        $data['user'] = $this->resource->user ? (object)[$this->resource->user->name] : null;

        return $data + [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'city' => $this->resource->city,
            'address' => $this->resource->address,
            'phone' => $this->resource->phone,
            'email' => $this->resource->email,
            'country' => $this->resource->country,
            'deleted_at' => $this->resource->deleted_at ? $this->resource->deleted_at->format('Y-m-d H:i:s') : null,
            'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'),
        ];

        return $data;
    }
}