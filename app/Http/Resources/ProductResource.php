<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'code'       => $this->code,
            'category'   => new CategoryResource($this->category),
            'price'      => $this->price,
            'quantity'   => $this->quantity,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            // 'warehouses' => $this->warehousess()->map(function ($warehouse) {
            //     return [
            //         'id'    => $warehouse->id,
            //         'name'  => $warehouse->name,
            //         'qty'   => $warehouse->pivot->qty,
            //         'price' => $warehouse->pivot->price,
            //         'cost'  => $warehouse->pivot->cost,
            //     ];
            // }),
        ];
    }
}
