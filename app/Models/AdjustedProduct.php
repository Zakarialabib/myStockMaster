<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Support\HasAdvancedFilter;

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

    public function adjustment() {
        return $this->belongsTo(Adjustment::class, 'adjustment_id', 'id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
