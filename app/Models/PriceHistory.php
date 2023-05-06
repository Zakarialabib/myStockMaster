<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PriceHistory
 *
 * @property int $id
 * @property int $product_id
 * @property int $warehouse_id
 * @property int $cost
 * @property string $effective_date
 * @property string $expiry_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|PriceHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceHistory whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceHistory whereEffectiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceHistory whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceHistory whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceHistory whereWarehouseId($value)
 * @mixin \Eloquent
 */
class PriceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'effective_date',
        'expiry_date',
        'cost',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
