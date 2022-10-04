<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Support\HasAdvancedFilter;

class SaleReturnPayment extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'sale_return_id',
        'amount',
        'payment_method',
        'payment_note',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'sale_return_id',
        'amount',
        'payment_method',
        'payment_note',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    public function saleReturn() {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id', 'id');
    }

    public function setAmountAttribute($value) {
        $this->attributes['amount'] = $value * 100;
    }

    public function getAmountAttribute($value) {
        return $value / 100;
    }

    public function getDateAttribute($value) {
        return Carbon::parse($value)->format('d M, Y');
    }

    public function scopeBySaleReturn($query) {
        return $query->where('sale_return_id', request()->route('sale_return_id'));
    }
}
