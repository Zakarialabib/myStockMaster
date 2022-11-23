<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use App\Support\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use HasAdvancedFilter;
    use Notifiable;

    public $orderable = [
        'id',
        'category_id',
        'name',
        'code',
        'barcode_symbology',
        'quantity',
        'cost',
        'price',
        'unit',
        'stock_alert',
        'order_tax',
        'tax_type',
        'note',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'category_id',
        'name',
        'code',
        'barcode_symbology',
        'quantity',
        'cost',
        'price',
        'unit',
        'stock_alert',
        'order_tax',
        'tax_type',
        'note',
        'created_at',
        'updated_at',
    ];

    public $fillable = [
        'category_id',
        'name',
        'code',
        'barcode_symbology',
        'quantity',
        'cost',
        'price',
        'unit',
        'stock_alert',
        'order_tax',
        'tax_type',
        'note',
    ];

    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes([
            'code' => Helper::genCode(),
        ], true);
        parent::__construct($attributes);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function setProductCostAttribute($value)
    {
        $this->attributes['cost'] = ($value * 100);
    }

    public function getProductCostAttribute($value)
    {
        return $value / 100;
    }

    public function setProductPriceAttribute($value)
    {
        $this->attributes['price'] = ($value * 100);
    }

    public function getProductPriceAttribute($value)
    {
        return $value / 100;
    }
}
