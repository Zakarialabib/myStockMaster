<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int                             $id
 * @property int                             $sale_return_id
 * @property string|null                     $product_id
 * @property string                          $name
 * @property string                          $code
 * @property int                             $quantity
 * @property numeric                         $price
 * @property numeric                         $unit_price
 * @property numeric                         $sub_total
 * @property numeric                         $discount_amount
 * @property string                          $discount_type
 * @property int                             $tax_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Product|null $product
 * @property mixed $product_discount_amount
 * @property mixed $product_tax_amount
 * @property-read SaleReturnPayment $saleReturn
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail whereSaleReturnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnDetail whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class SaleReturnDetail extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use HasAdvancedFilter;

    public const ATTRIBUTES = [
        'id',
        'sale_return_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'created_at',
        'updated_at',

    ];

    public array $orderable = self::ATTRIBUTES;

    public array $filterable = self::ATTRIBUTES;

    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\SaleReturnPayment, $this>
     */
    public function saleReturn(): BelongsTo
    {
        return $this->belongsTo(SaleReturnPayment::class, 'sale_return_id', 'id');
    }

    /**
     * get price attribute
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
            set: fn ($value): int|float => $value * 100,
        );
    }

    /**
     * Interact with unit price
     */
    protected function unitPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
            set: fn ($value): int|float => $value * 100,
        );
    }

    /**
     * get subtotal attribute
     */
    protected function subTotal(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
            set: fn ($value): int|float => $value * 100,
        );
    }

    /**
     * Interact with product discount amount
     */
    protected function productDiscountAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
            set: fn ($value): int|float => $value * 100,
        );
    }

    /**
     * Interact with product tax amount
     */
    protected function productTaxAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
            set: fn ($value): int|float => $value * 100,
        );
    }
}
