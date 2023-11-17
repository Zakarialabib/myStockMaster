<?php

namespace App\Http\Resources;

use App\Models\Supplier;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \App\Models\Supplier  $resource
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->resource->id,
            'uuid' => $this->resource->uuid,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'address' => $this->resource->address,
            'city' => $this->resource->city,
            'country' => $this->resource->country,
            'tax_number' => $this->resource->tax_number,
            'deleted_at' => $this->resource->deleted_at ? $this->resource->deleted_at->format('Y-m-d H:i:s') : null,
            'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'updated_by' =>  $this->resource->updated_by->format('Y-m-d H:i:s'),
        ];
    }
}
