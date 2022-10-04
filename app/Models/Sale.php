<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;

class Sale extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'reference',
        'customer_id',
        'date',
        'status',
        'shipping_amount',
        'paid_amount',
        'total_amount',
        'due_amount',
        'tax_amount',
        'discount_amount',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'reference',
        'customer_id',
        'date',
        'status',
        'shipping_amount',
        'paid_amount',
        'total_amount',
        'due_amount',
        'tax_amount',
        'discount_amount',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    public function saleDetails() {
        return $this->hasMany(SaleDetails::class, 'sale_id', 'id');
    }

    public function salePayments() {
        return $this->hasMany(SalePayment::class, 'sale_id', 'id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = Sale::max('id') + 1;
            $model->reference = make_reference_id('SL', $number);
        });
    }

    public function scopeCompleted($query) {
        return $query->where('status', 'Completed');
    }

    public function getShippingAmountAttribute($value) {
        return $value / 100;
    }

    public function getPaidAmountAttribute($value) {
        return $value / 100;
    }

    public function getTotalAmountAttribute($value) {
        return $value / 100;
    }

    public function getDueAmountAttribute($value) {
        return $value / 100;
    }

    public function getTaxAmountAttribute($value) {
        return $value / 100;
    }

    public function getDiscountAmountAttribute($value) {
        return $value / 100;
    }
}
