<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class SalePayment extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'sale_id',
        'payment_method',
        'reference',
        'amount',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'sale_id',
        'payment_method',
        'reference',
        'amount',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'date',
        'reference',
        'amount',
        'note',
        'sale_id',
        'payment_method',
    ];

    public function sale():BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
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

    public function scopeBySale($query)
    {
        return $query->whereSaleId(request()->route('sale_id'));
    }
}
