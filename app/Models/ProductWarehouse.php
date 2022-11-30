<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductWarehouse extends Model
{
    protected $table = 'product_warehouse';

    protected $fillable = [
        'product_id', 'warehouse_id', 'qte',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'warehouse_id' => 'integer',
        'qte' => 'double',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo('App\Models\Warehouse');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo('App\Models\ProductVariant');
    }
}
