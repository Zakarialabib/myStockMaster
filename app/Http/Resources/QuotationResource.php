<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'customer'        => new CustomerResource($this->customer),  // Include customer resource for this quotation.
            'user'            => new UserResource($this->user),
            'warehouse'       => new WarehouseResource($this->warehouse),
            'date'            => $this->date,
            'reference'       => $this->reference,
            'tax_percentage'  => (float) $this->tax_percentage,
            'discount_amount' => (float) $this->discount_amount,
            'shipping_amount' => (float) $this->shipping_amount,
            'total_amount'    => (float) $this->total_amount,
            'status'          => $this->status,
            'sent_on'         => date('Y-m-d', $this->sent_on),
            'expires_on'      => date('Y-m-d', $this->expires_on),
            'note'            => $this->note,
        ];
    }
}
