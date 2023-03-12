<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\SaleReturnDetail
 *
 * @property int $id
 * @property int $sale_return_id
 * @property int|null $product_id
 * @property string $name
 * @property string $code
 * @property int $quantity
 * @property int $price
 * @property int $unit_price
 * @property int $sub_total
 * @property int $discount_amount
 * @property string $discount_type
 * @property int $tax_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read mixed $product_discount_amount
 * @property-read mixed $product_tax_amount
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\SaleReturnPayment $saleReturn
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail whereSaleReturnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnDetail whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class SaleReturnDetail extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'sale_return_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'sale_return_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function saleReturn(): BelongsTo
    {
        return $this->belongsTo(SaleReturnPayment::class, 'sale_return_id', 'id');
    }

    /**
     * get price attribute
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /**
     * Interact with unit price
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function unitPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /**
     * get subtotal attribute
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function subTotal(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /**
     * Interact with shipping amount
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function productDiscountAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /**
     * Interact with shipping amount
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function productTaxAmountAttribute(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }
}
