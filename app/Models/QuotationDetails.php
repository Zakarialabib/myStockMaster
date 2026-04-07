<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int                             $id
 * @property int                             $quotation_id
 * @property string|null                     $product_id
 * @property string                          $name
 * @property string                          $code
 * @property int                             $quantity
 * @property numeric                         $price
 * @property numeric                         $unit_price
 * @property numeric                         $sub_total
 * @property numeric                         $product_discount_amount
 * @property string                          $product_discount_type
 * @property numeric                         $product_tax_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Product|null $product
 * @property-read mixed $product_tax_amount_attribute
 * @property-read Quotation $quotation
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails whereProductDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails whereProductDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails whereProductTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails whereQuotationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuotationDetails whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class QuotationDetails extends Model
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Quotation, $this>
     */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
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
