<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
