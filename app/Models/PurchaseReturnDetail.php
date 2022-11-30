<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseReturnDetail extends Model
{
    protected $guarded = [];

    protected $with = ['product'];

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function purchaseReturn():BelongsTo
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id', 'id');
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
