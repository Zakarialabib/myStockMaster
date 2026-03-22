<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProductWarehouse extends Pivot
{
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'product_id'   => 'string',
            'warehouse_id' => 'integer'
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

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
