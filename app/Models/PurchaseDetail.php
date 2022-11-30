<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseDetail extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'purchase_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'purchase_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $with = ['product'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
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
