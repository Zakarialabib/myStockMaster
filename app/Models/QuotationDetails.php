<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationDetails extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'quotation_id',
        'product_id',
        'name',
        'code',
        'quantity',
        'price',
        'unit_price',
        'sub_total',
        'product_discount_amount',
        'product_discount_type',
        'product_tax_amount',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'quotation_id',
        'product_id',
        'name',
        'code',
        'quantity',
        'price',
        'unit_price',
        'sub_total',
        'product_discount_amount',
        'product_discount_type',
        'product_tax_amount',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $with = ['product'];

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function quotation():BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }

    public function getPriceAttribute($value)
    {
        return $value / 100;
    }

    public function getUnitPriceAttribute($value)
    {
        return $value / 100;
    }

    public function getSubTotalAttribute($value)
    {
        return $value / 100;
    }

    public function getProductDiscountAmountAttribute($value)
    {
        return $value / 100;
    }

    public function getProductTaxAmountAttribute($value)
    {
        return $value / 100;
    }
}
