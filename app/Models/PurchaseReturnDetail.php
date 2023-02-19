<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PurchaseReturnDetail
 *
 * @property int $id
 * @property int $purchase_return_id
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
 * @property-read \App\Models\PurchaseReturn $purchaseReturn
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail wherePurchaseReturnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnDetail whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PurchaseReturnDetail extends Model
{
    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function purchaseReturn(): BelongsTo
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id', 'id');
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
