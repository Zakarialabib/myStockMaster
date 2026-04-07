<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int                             $id
 * @property string|null                     $sale_id
 * @property string|null                     $product_id
 * @property string|null                     $user_id
 * @property int|null                        $warehouse_id
 * @property string                          $name
 * @property string                          $code
 * @property int                             $quantity
 * @property numeric                         $price
 * @property numeric                         $unit_price
 * @property numeric                         $sub_total
 * @property numeric                         $product_discount_amount
 * @property string                          $product_discount_type
 * @property int                             $product_tax_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Product|null $product
 * @property-read mixed $product_tax_amount_attribute
 * @property-read Sale|null $sale
 * @property-read Warehouse|null $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails whereProductDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails whereProductDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails whereProductTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDetails whereWarehouseId($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class SaleDetails extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'sale_id',
        'product_id',
        'warehouse_id',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Sale, $this>
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Warehouse, $this>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * get price attribute
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /**
     * Interact with unit price
     */
    protected function unitPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /**
     * get subtotal attribute
     */
    protected function subTotal(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /**
     * product discount amount attribute
     */
    protected function productDiscountAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /**
     * Interact with shipping amount
     */
    protected function productTaxAmountAttribute(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }
}
