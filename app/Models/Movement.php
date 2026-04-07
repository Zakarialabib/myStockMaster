<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MovementType;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int                             $id
 * @property MovementType                    $type
 * @property int                             $quantity
 * @property numeric                         $price
 * @property \Illuminate\Support\Carbon      $date
 * @property string                          $movable_type
 * @property string                          $movable_id
 * @property string                          $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Illuminate\Database\Eloquent\Model $movable
 * @property-read ProductWarehouse|null $productWarehouse
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereMovableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereMovableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movement whereUserId($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Movement extends Model
{
    protected $casts = [
        'date' => 'date',
    ];

    use HasFactory;

    protected $fillable = [
        'type',
        'quantity',
        'price',
        'date',
        'movable_id',
        'movable_type',
        'user_id',
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
            'type' => MovementType::class,
        ];
    }

    public function movable()
    {
        return $this->morphTo();
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\ProductWarehouse, $this>
     */
    public function productWarehouse(): BelongsTo
    {
        return $this->belongsTo(ProductWarehouse::class);
    }
}
