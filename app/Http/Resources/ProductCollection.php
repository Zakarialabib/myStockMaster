<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data'  => ProductResource::collection($this->collection),
            'links' => [
                'meta' => ['product_count' => $this->collection->count()],
            ],
        ];
    }
}
