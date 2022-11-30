<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdjustedProduct extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'product_id',
        'quantity',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'product_id',
        'quantity',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $with = ['product'];

    public function adjustment(): BelongsTo
    {
        return $this->belongsTo(Adjustment::class, 'adjustment_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
