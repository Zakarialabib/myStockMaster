<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;
use App\Models\Product;

class SaleDetails extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $with = ['product'];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function sale() {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function getPriceAttribute($value) {
        return $value / 100;
    }

    public function getUnitPriceAttribute($value) {
        return $value / 100;
    }

    public function getSubTotalAttribute($value) {
        return $value / 100;
    }

    public function getProductDiscountAmountAttribute($value) {
        return $value / 100;
    }

    public function getProductTaxAmountAttribute($value) {
        return $value / 100;
    }
}
