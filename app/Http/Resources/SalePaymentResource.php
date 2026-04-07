<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SalePaymentResource extends JsonResource
{
    #[\Override]
    public function toArray($request)
    {
        return [
            'id' => $request->id,
            'sale_id' => $request->sale_id,
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'date' => \Illuminate\Support\Facades\Date::parse($request->date)->format('Y-M-D'),
            'reference' => $request->reference,
            'payment_method' => $request->payment_method,
            'note' => $request?->note,
            'created_at' => \Illuminate\Support\Facades\Date::parse($request->created_at)->format('Y-M-D H:i:s'),
            'updated_at' => $request?->updated_at ? \Illuminate\Support\Facades\Date::parse($request?->updated_at) : \Illuminate\Support\Facades\Date::now(),
        ];
    }
}
