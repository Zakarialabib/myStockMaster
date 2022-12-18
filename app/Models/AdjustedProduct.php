<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\AdjustedProduct
 *
 * @property int $id
 * @property int $adjustment_id
 * @property int $product_id
 * @property int $quantity
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Adjustment $adjustment
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|AdjustedProduct advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|AdjustedProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdjustedProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdjustedProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdjustedProduct whereAdjustmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdjustedProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdjustedProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdjustedProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdjustedProduct whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdjustedProduct whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdjustedProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class AdjustedProduct extends Model
{
    use HasAdvancedFilter;

   /** 
     * @var string[] 
    */
    public $orderable = [
        'id',
        'product_id',
        'quantity',
        'created_at',
        'updated_at',
    ];

   /** 
     * @var string[] 
    */
    public $filterable = [
        'id',
        'product_id',
        'quantity',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    /** @return BelongsTo<Adjustment> */
    public function adjustment(): BelongsTo
    {
        return $this->belongsTo(Adjustment::class, 'adjustment_id', 'id');
    }

    /** @return BelongsTo<Product> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
