<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Resources\Json\Resource;

class CustomerResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \App\Models\Customer $resource
     * @return array
     */
    public function toArray($request)
    {
        // Relationships
        // $data['user'] = $this->resource->user ? UserResource::make($this->resource->user)->only(['id', 'name', 'email'] : null;
        // $data['customerGroup'] = CustomerGroupResource::make($this->resource->customerGroup);
        // $data['wallet'] = WalletResource::make($this->resource->wallet);

        return [
            'id' => $this->resource->id,
            'uuid' => $this->resource->uuid,
            'name' => $this->resource->name,
            'phone' => $this->resource->phone,
            'email' => $this->resource->email,
            'city' => $this->resource->city,
            'country' => $this->resource->country,
            'address' => $this->resource->address,
            'taxNumber' => $this->resource->tax_number,
            'customerGroup' => $data['customerGroup'],
            'user' => $data['user'],
            'wallet' => $data['wallet'],
            'createdAt' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->resource->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}

  