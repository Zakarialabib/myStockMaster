<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * App\Models\QuotationDetails
 *
 * @property int $id
 * @property int $quotation_id
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
 * @property-read \App\Models\Quotation $quotation
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails whereProductDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails whereProductDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails whereProductTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails whereQuotationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuotationDetails extends Model
{
    use HasAdvancedFilter;

    /** @var string[] */
    public $orderable = [
        'id',
        'quotation_id',
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
        'created_at',
        'updated_at',
    ];

    /** @var string[] */
    public $filterable = [
        'id',
        'quotation_id',
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
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    /** @return BelongsTo<Product> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /** @return BelongsTo<Quotation> */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
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
