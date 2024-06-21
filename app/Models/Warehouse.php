<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Warehouse extends Model
{
    use HasAdvancedFilter;

    protected const ATTRIBUTES = [
        'id',
        'name',
        'city',
        'phone',
        'email',
        'country',
        'created_at',
        'updated_at',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'city', 'address', 'phone', 'email', 'country',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_warehouse', 'warehouse_id', 'user_id');
    }

    /** @return BelongsToMany<Product> */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_warehouse', 'warehouse_id', 'product_id')
            ->withPivot('price', 'cost', 'qty', 'old_price', 'stock_alert');
    }

    public function productWarehouse()
    {
        return $this->hasMany(ProductWarehouse::class, 'warehouse_id');
    }

    public function getTotalQuantityAttribute()
    {
        return $this->productWarehouse()->sum('qty');
    }

    public function getStockValueAttribute(): float
    {
        return $this->productWarehouse()->sum(DB::raw('qty * cost')) / 100;
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class)->using(UserWarehouse::class)
            ->withPivot('user_id', 'warehouse_id');
    }
}
