<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**  * Transform the resource into an array. * @param \Illuminate\Http\Request $request  */
    public function toArray($request)
    {
        return [
            'id'                  => $this->id, // or use getIdForApi() in case of UUIDs
            'date'                => $this->date->format('Y-m-d'), // format the date for a specific output format (e.g., Y-m-d)
            'reference'           => $this->reference,
            'supplier_id'         => $this->supplier_id,
            'user_id'             => $this->user_id,
            'warehouse_id'        => $this->warehouse_id, // or use getWarehouseForApi() for relationship resolution (if exists)
            'tax_percentage'      => $this->tax_percentage ?? 0,
            'tax_amount'          => $this->tax_amount,
            'discount_percentage' => $this->discount_percentage ?? 0, // or use getDiscountForApi() for relationship resolution (if exists)
            'discount_amount'     => $this->discount_amount,
            'shipping_amount'     => $this->shipping_amount,
            'total_amount'        => $this->total_amount, // or use getTotalAmountForApi() for relationship resolution (if exists)
            'paid_amount'         => $this->paid_amount,
            'due_amount'          => $this->due_amount ?? 0, // or use getDueAmountForApi() for relationship resolution (if exists)
            'status'              => $this->status, // or use getStatusLabelForApi() for labeling statuses (e.g., "pending", "paid")
            'payment_status'      => $this->payment_status, // or use getPaymentStatusLabelForApi() for labeling payment statuses (e.g., "due", "partial", "fully paid")
            'payment_method'      => $this->payment_method, // or use getPaymentMethodLabelForApi() for labeling payment methods (e.g., "cash", "card")
            'document'            => $this->document, // or use getDocumentUrlForApi() to resolve URLs from document field (if exists)
            'note'                => $this->note, // or use getNoteLabelForApi() for labeling notes (e.g., "received damaged goods")
        ];
    }
}
