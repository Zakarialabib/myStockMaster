<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int                             $id
 * @property int                             $adjustment_id
 * @property string|null                     $product_id
 * @property int|null                        $warehouse_id
 * @property int                             $quantity
 * @property string                          $type
 * @property string|null                     $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Adjustment|null $adjustment
 * @property-read Product|null $product
 * @property-read Warehouse|null $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdjustedProduct advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdjustedProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdjustedProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdjustedProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdjustedProduct whereAdjustmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdjustedProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdjustedProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdjustedProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdjustedProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdjustedProduct whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdjustedProduct whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdjustedProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdjustedProduct whereWarehouseId($value)
 *
 * @mixin \Eloquent
 */
class AdjustedProduct extends Model
{
    use HasAdvancedFilter;

    public const ATTRIBUTES = [
        'id',
        'product_id',
        'warehouse_id',
        'quantity',
        'created_at',
        'updated_at',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    protected $guarded = [];

    public function adjustment(): BelongsTo
    {
        return $this->belongsTo(Adjustment::class, 'adjustment_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
