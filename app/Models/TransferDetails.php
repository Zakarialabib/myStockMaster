<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read Product|null $product
 * @property-read Warehouse|null $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferDetails advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferDetails query()
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class TransferDetails extends Model
{
    use HasAdvancedFilter;
    use HasFactory;

    protected const ATTRIBUTES = [
        'id',
        'product_id',
        'warehouse_id',
        'quantity',
        'created_at',
        'updated_at',
    ];

    public array $orderable = self::ATTRIBUTES;

    public array $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Warehouse, $this>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
