<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\UserWarehouse
 *
 * @property int $user_id
 * @property int $warehouse_id
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Models\Warehouse> $assignedWarehouses
 * @property-read int|null $assigned_warehouses_count
 * @method static \Illuminate\Database\Eloquent\Builder|UserWarehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWarehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWarehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWarehouse whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWarehouse whereWarehouseId($value)
 * @mixin \Eloquent
 */
class UserWarehouse extends Model
{
    protected $table = 'user_warehouse';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 'warehouse_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'warehouse_id' => 'integer',
    ];

    /** @return HasMany<Warehouse> */
    public function assignedWarehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class, 'id', 'warehouse_id');
    }
}
