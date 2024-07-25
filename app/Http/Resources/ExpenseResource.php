<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \App\Models\Expense $resource
     * @return array
     */
    public function toArray($request)
    {
        $category = $this->resource->category;
        $user = $this->resource->user;
        $warehouse = $this->resource->warehouse;

        return [
            'id'         => $this->resource->id,
            'category'   => new CategoryResource($category),
            'user'       => new UserResource($user),
            'warehouse'  => new WarehouseResource($warehouse),
            'date'       => $this->resource->date,
            'reference'  => $this->resource->reference,
            'details'    => $this->resource->details,
            'amount'     => $this->resource->amount,
            'document'   => $this->resource->document,
            'deleted_at' => $this->resource->deleted_at ?: null, // Only return deleted_at if not null
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
