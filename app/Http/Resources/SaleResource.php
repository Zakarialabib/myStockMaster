<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array. *
     * @param \Illuminate\Http\Request $request
     * */
    public function toArray($request)
    {
        return [
            'id'                  => $this->id, // relationship with user model or custom method for uuid generation (optional)
            'date'                => date('Y-m-d', strtotime($this->date)), // format the date as per requirement
            'reference'           => $this->reference,
            'customer_id'         => $this->customer_id, // relationship with customer model or custom method for uuid generation (optional)
            'user_id'             => $this->user_id, // relationship with user model or custom method for uuid generation (optional)
            'warehouse_id'        => $this->warehouse_id, // relationship with warehouse model or custom method for uuid generation (optional)
            'tax_percentage'      => $this->tax_percentage,
            'tax_amount'          => $this->tax_amount,
            'discount_percentage' => $this->discount_percentage, // negative value may cause issues in some json encoders (optional)
            'discount_amount'     => $this->discount_amount,
            'shipping_amount'     => $this->shipping_amount,
            'total_amount'        => $this->total_amount, // use a custom method for calculating total amount if required (optional)
            'paid_amount'         => $this->paid_amount,
            'due_amount'          => $this->due_amount,
            'payment_date'        => date('Y-m-d', strtotime($this->payment_date)), // format the payment date as per requirement (optional)
            'status'              => $this->status, // use a custom method for converting status to human readable format if required (optional)
            'payment_status'      => $this->payment_status,
            'payment_method'      => $this->payment_method, // use a custom method for converting payment methods into text representation if required (optional)
            'shipping_status'     => $this->shipping_status, // use a custom method for converting shipping statuses into text representation if required (optional)
            'document'            => $this->document, // optional field to store document or receipt image urls in database and return them here (optional)
            'note'                => $this->note,
        ];
    }
}
