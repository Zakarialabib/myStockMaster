<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * App\Models\SaleDetails
 *
 * @property int $id
 * @property int $sale_id
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
 * @property-read \App\Models\Sale $sale
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails whereProductDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails whereProductDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails whereProductTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleDetails whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SaleDetails extends Model
{
    use HasAdvancedFilter;

    /** @var string[] */
    public $orderable = [
        'id',
        'sale_id',
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
    ];

    /** @var string[] */
    public $filterable = [
        'id',
        'sale_id',
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
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
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
    ];

    /** @return BelongsTo<Product> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /** @return BelongsTo<Sale> */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
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
     * product discount amount attribute
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
