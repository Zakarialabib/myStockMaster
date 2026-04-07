<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int                             $id
 * @property int                             $purchase_return_id
 * @property string|null                     $product_id
 * @property string                          $name
 * @property string                          $code
 * @property int                             $quantity
 * @property numeric                         $price
 * @property numeric                         $unit_price
 * @property numeric                         $sub_total
 * @property numeric                         $discount_amount
 * @property string                          $discount_type
 * @property numeric                         $tax_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Product|null $product
 * @property mixed $product_discount_amount
 * @property mixed $product_tax_amount
 * @property-read PurchaseReturn $purchaseReturn
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail wherePurchaseReturnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnDetail whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PurchaseReturnDetail extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($detail) {
            $detail->purchaseReturn->syncTotals();
        });

        static::deleted(function ($detail) {
            $detail->purchaseReturn->syncTotals();
        });
    }

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
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    /**
     * Interact with unit price
     */
    protected function unitPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    /**
     * get subtotal attribute
     */
    protected function subTotal(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    /**
     * Interact with product discount amount
     */
    protected function productDiscountAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    /**
     * Interact with product tax amount
     */
    protected function productTaxAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
}
