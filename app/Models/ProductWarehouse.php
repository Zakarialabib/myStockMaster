<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductWarehouse extends Pivot
{
    use SoftDeletes;

    protected $table = 'product_warehouse';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id', 'warehouse_id', 'qty', 'price', 'cost',
    ];

    protected $casts = [
        'product_id'   => 'integer',
        'warehouse_id' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function productMovements()
    {
        return $this->hasMany(Movement::class);
    }
}
