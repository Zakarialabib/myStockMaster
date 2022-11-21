<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;

class SaleReturn extends Model
{
    use HasAdvancedFilter;

    const PaymentPending =  '0' ;
    const PaymentPaid =  '1' ;
    const PaymentPartial =  '2' ;
    const PaymentDue =  '3' ;

    const SaleReturnPending =  '0' ;
    const SaleReturnCompleted =  '1' ;
    const SaleReturnCanceld =  '2' ;

    public $orderable = [
        'id',
        'date',
        'reference',
        'supplier_id',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'paid_amount',
        'due_amount',
        'status',
        'payment_status',
        'payment_method',
        'note',
        'customer_id',
    ];

    public $filterable = [
        'id',
        'date',
       'reference',
       'supplier_id',
       'tax_percentage',
       'tax_amount',
       'discount_percentage',
       'discount_amount',
       'shipping_amount',
       'total_amount',
       'paid_amount',
       'due_amount',
       'status',
       'payment_status',
       'payment_method',
       'note',
       'customer_id',
    ];

    protected $fillable = [
        'date',
       'reference',
       'supplier_id',
       'tax_percentage',
       'tax_amount',
       'discount_percentage',
       'discount_amount',
       'shipping_amount',
       'total_amount',
       'paid_amount',
       'due_amount',
       'status',
       'payment_status',
       'payment_method',
       'note',
       'customer_id',
    ];

    public function saleReturnDetails() {
        return $this->hasMany(SaleReturnDetail::class, 'sale_return_id', 'id');
    }

    public function saleReturnPayments() {
        return $this->hasMany(SaleReturnPayment::class, 'sale_return_id', 'id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = SaleReturn::max('id') + 1;
            $model->reference = make_reference_id('SLRN', $number);;
        });
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
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
