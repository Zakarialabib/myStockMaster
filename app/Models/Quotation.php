<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Models\Customer;
use App\Support\HasAdvancedFilter;

class Quotation extends Model
{  
    use HasAdvancedFilter;
    public $orderable = [
        'id',
        'quotation_code',
        'customer_id',
        'quotation_date',
        'quotation_status',
        'quotation_note',
        'quotation_total',
        'quotation_discount',
        'quotation_tax',
        'quotation_grand_total',
        'created_at',
        'updated_at',
    ];
    
    public $filterable = [
        'id',
        'quotation_code',
        'customer_id',
        'quotation_date',
        'quotation_status',
        'quotation_note',
        'quotation_total',
        'quotation_discount',
        'quotation_tax',
        'quotation_grand_total',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    public function quotationDetails() {
        return $this->hasMany(QuotationDetails::class, 'quotation_id', 'id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = Quotation::max('id') + 1;
            $model->reference = make_reference_id('QT', $number);
        });
    }

    public function getDateAttribute($value) {
        return Carbon::parse($value)->format('d M, Y');
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
