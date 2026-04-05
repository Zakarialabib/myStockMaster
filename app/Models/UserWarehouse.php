<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int                             $id
 * @property int                             $user_id
 * @property int                             $warehouse_id
 * @property int                             $is_default
 * @property int                             $status
 * @property string|null                     $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Warehouse> $assignedWarehouses
 * @property-read int|null $assigned_warehouses_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarehouse whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarehouse whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarehouse whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarehouse whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarehouse whereWarehouseId($value)
 *
 * @mixin \Eloquent
 */
class UserWarehouse extends Pivot
{
    protected $table = 'user_warehouse';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'warehouse_id',
        // 'default_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'warehouse_id' => 'integer',
        ];
    }

    /** @return HasMany<Warehouse> */
    public function assignedWarehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class, 'id', 'warehouse_id');
    }
}
