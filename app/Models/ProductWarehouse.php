<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int                             $id
 * @property string                          $product_id
 * @property int                             $warehouse_id
 * @property int|float                       $price
 * @property int|float                       $cost
 * @property int|float                       $old_price
 * @property int                             $qty
 * @property int                             $stock_alert
 * @property int                             $is_ecommerce
 * @property int                             $is_discount
 * @property string|null                     $discount_date
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Product|null $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Movement> $productMovements
 * @property-read int|null $product_movements_count
 * @property-read Warehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse whereDiscountDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse whereIsDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse whereIsEcommerce($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse whereOldPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse whereStockAlert($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse whereWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductWarehouse withoutTrashed()
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class ProductWarehouse extends Pivot
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use SoftDeletes;

    protected $table = 'product_warehouse';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id', 'warehouse_id',
        'qty', 'price', 'cost', 'old_price', 'stock_alert',
        'is_discount', 'discount_date', 'is_ecommerce',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'product_id' => 'string',
            'warehouse_id' => 'integer',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Warehouse, $this>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Movement, $this>
     */
    public function productMovements(): HasMany
    {
        return $this->hasMany(Movement::class);
    }

    protected function cost(): Attribute
    {
        return Attribute::make(
            get: static fn ($value): int|float => $value / 100,
            set: static fn ($value): int => (int) ($value * 100),
        );
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: static fn ($value): int|float => $value / 100,
            set: static fn ($value): int => (int) ($value * 100),
        );
    }

    protected function old_price(): Attribute
    {
        return Attribute::make(
            get: static fn ($value): int|float => $value / 100,
            set: static fn ($value): int => (int) ($value * 100),
        );
    }
}
