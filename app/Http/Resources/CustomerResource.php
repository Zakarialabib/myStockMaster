<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Resources\Json\Resource;

class CustomerResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Customer $resource
     * @return array
     */
    public function toArray($request)
    {
        // Relationships
        // $data['user'] = $request->user ? UserResource::make($request->user)->only(['id', 'name', 'email'] : null;
        // $data['customerGroup'] = CustomerGroupResource::make($request->customerGroup);

        return [
            'id'            => $request->id,
            'uuid'          => $request->uuid,
            'name'          => $request->name,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'city'          => $request->city,
            'country'       => $request->country,
            'address'       => $request->address,
            'taxNumber'     => $request->tax_number,
            'customerGroup' => $data['customerGroup'],
            'user'          => $data['user'],
            'createdAt'     => $request->created_at->format('Y-m-d H:i:s'),
            'updatedAt'     => $request->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
