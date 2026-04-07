<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string                          $id
 * @property string                          $reference
 * @property int                             $from_warehouse_id
 * @property int                             $to_warehouse_id
 * @property int                             $item
 * @property int                             $total_qty
 * @property int                             $total_tax
 * @property numeric                         $total_cost
 * @property numeric                         $total_amount
 * @property float|null                      $shipping
 * @property string|null                     $document
 * @property int                             $status
 * @property string|null                     $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Warehouse|null $fromWarehouse
 * @property-read Warehouse|null $toWarehouse
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TransferDetails> $transferDetails
 * @property-read int|null $transfer_details_count
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereFromWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereToWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereTotalQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereTotalTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Transfer extends Model
{
    use HasAdvancedFilter;
    use HasFactory;
    use HasUuid;

    public const ATTRIBUTES = [
        'id',
        'reference',
        'from_warehouse_id',
        'to_warehouse_id',
        'total_qty',
        'total_tax',
        'total_cost',
        'total_amount',
        'shipping',
        'status',
        'note',
    ];

    public array $orderable = self::ATTRIBUTES;

    public array $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'reference',
        'from_warehouse_id',
        'to_warehouse_id',
        'item',
        'total_qty',
        'total_tax',
        'total_cost',
        'total_amount',
        'shipping',
        'document',
        'status',
        'note',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Warehouse, $this>
     */
    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(
            related: Warehouse::class,
            foreignKey: 'from_warehouse_id',
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Warehouse, $this>
     */
    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(
            related: Warehouse::class,
            foreignKey: 'to_warehouse_id',
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
        );
    }

    public function transferDetails()
    {
        return $this->hasMany(TransferDetails::class);
    }
}
