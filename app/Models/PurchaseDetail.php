<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PurchaseDetail
 *
 * @property int $id
 * @property int $purchase_id
 * @property int|null $product_id
 * @property string $name
 * @property string $code
 * @property int $quantity
 * @property int $price
 * @property int $unit_price
 * @property int $sub_total
 * @property int $product_discount_amount
 * @property string $product_discount_type
 * @property int $product_tax_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Purchase $purchase
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail whereProductDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail whereProductDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail whereProductTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail wherePurchaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PurchaseDetail extends Model
{
    use HasAdvancedFilter;

   /** 
     * @var string[] 
    */
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

   /** 
     * @var string[] 
    */
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

    /** 
     * @return BelongsTo<Product> 
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /** 
     * @return BelongsTo<Purchase> 
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getPriceAttribute($value)
    {
        return $value / 100;
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getUnitPriceAttribute($value)
    {
        return $value / 100;
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getSubTotalAttribute($value)
    {
        return $value / 100;
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getProductDiscountAmountAttribute($value)
    {
        return $value / 100;
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getProductTaxAmountAttribute($value)
    {
        return $value / 100;
    }
}
