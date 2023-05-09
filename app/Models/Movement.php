<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Eloquent;

/**
 * App\Models\Movement
 *
 * @property int $id
 * @property string $type
 * @property int $quantity
 * @property string $price
 * @property string $date
 * @property int $movable_id
 * @property string $movable_type
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|Eloquent $movable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Movement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereMovableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereMovableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movement whereUserId($value)
 * @mixin \Eloquent
 */

class Movement extends Model
{
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

    public function movable()
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
        );
    }

    public function productWarehouse()
    {
        return $this->belongsTo(ProductWarehouse::class);
    }
}
