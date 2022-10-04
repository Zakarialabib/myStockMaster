<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;
use Illuminate\Support\Carbon;

class SalePayment extends Model
{
    use HasAdvancedFilter;
    public $orderable = [
        'id',
        'sale_id',
        'payment_method',
        'payment_reference',
        'amount',
        'created_at',
        'updated_at',
    ];
    
    public $filterable = [
        'id',
        'sale_id',
        'payment_method',
        'payment_reference',
        'amount',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    public function sale() {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
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

    public function scopeBySale($query) {
        return $query->where('sale_id', request()->route('sale_id'));
    }
}
