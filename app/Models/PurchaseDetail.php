<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int                             $id
 * @property string                          $purchase_id
 * @property string|null                     $product_id
 * @property int|null                        $warehouse_id
 * @property string                          $name
 * @property string                          $code
 * @property numeric                         $quantity
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
 * @property-read Purchase|null $purchase
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail whereProductDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail whereProductDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail whereProductTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail wherePurchaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseDetail whereWarehouseId($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class PurchaseDetail extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Purchase, $this>
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
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
     * Interact with shipping amount
     */
    protected function productTaxAmountAttribute(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /**
     * Interact with shipping amount
     */
    protected function productDiscountAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }
}
