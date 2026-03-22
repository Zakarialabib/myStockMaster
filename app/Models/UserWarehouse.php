<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        // 'default_id',
    ];

        /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id'      => 'integer',
            'warehouse_id' => 'integer'
        ];
    }

    /** @return HasMany<Warehouse> */
    public function assignedWarehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class, 'id', 'warehouse_id');
    }
}
