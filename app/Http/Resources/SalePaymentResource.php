<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SalePaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $request->id,
            'sale_id'        => $request->sale_id,
            'user_id'        => $request->user_id,
            'amount'         => $request->amount,
            'date'           => Carbon::parse($request->date)->format('Y-M-D'),
            'reference'      => $request->reference,
            'payment_method' => $request->payment_method,
            'note'           => optional($request)->note,
            'created_at'     => Carbon::parse($request->created_at)->format('Y-M-D H:i:s'),
            'updated_at'     => optional($request)->updated_at ? Carbon::parse(optional($request)->updated_at) : Carbon::now(),
        ];
    }
}
