<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class PurchaseReturnPayment extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'date',
        'reference',
        'amount',
        'note',
        'purchase_return_id',
        'payment_method',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'date',
        'reference',
        'amount',
        'note',
        'purchase_return_id',
        'payment_method',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    public function purchaseReturn(): BelongsTo
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id', 'id');
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value * 100;
    }

    public function getAmountAttribute($value)
    {
        return $value / 100;
    }

    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d M, Y');
    }

    public function scopeByPurchaseReturn($query)
    {
        return $query->wherePurchaseReturnId(request()->route('purchase_return_id'));
    }
}
