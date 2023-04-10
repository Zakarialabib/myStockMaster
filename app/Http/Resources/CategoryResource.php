<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * <<<<<<< HEAD
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|JsonSerializable
     * =======
     * @return array|\Illuminate\Contracts\Support\Arrayable|JsonSerializable
     * >>>>>>> Api
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
        ];
    }
}
