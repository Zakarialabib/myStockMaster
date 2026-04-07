<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int                             $id
 * @property string|null                     $product_id
 * @property int|null                        $warehouse_id
 * @property int                             $cost
 * @property string|null                     $effective_date
 * @property string|null                     $expiry_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Product|null $product
 * @property-read Warehouse|null $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceHistory whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceHistory whereEffectiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceHistory whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceHistory whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceHistory whereWarehouseId($value)
 *
 * @mixin \Eloquent
 */
class PriceHistory extends Model
{
    use HasFactory;

    protected const ATTRIBUTES = [
        'id', 'product_id', 'price', 'effective_date', 'expiration_date',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'product_id',
        'warehouse_id',
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
