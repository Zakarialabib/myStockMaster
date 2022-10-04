<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\NotifyQuantityAlert;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Support\HasAdvancedFilter;
use App\Support\Helper;

class Product extends Model implements HasMedia
{
    use HasAdvancedFilter;
    use InteractsWithMedia;
    
    public $orderable = [
        'id',
        'name',
        'code',
        'category_id',
        'quantity',
        'alert_quantity',
        'price',
        'tax',
        'discount',
        'image',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'name',
        'code',
        'category_id',
        'quantity',
        'alert_quantity',
        'price',
        'tax',
        'discount',
        'image',
        'created_at',
        'updated_at',
    ];

    // Add those columns to table : tinyint-> "website_featured","catalogue_featured"
    
    protected $guarded = [];

    protected $with = ['media'];

    public function __construct(array $attributes = array())
    {
        $this->setRawAttributes(array(
            'code' => Helper::genCode()
        ), true);
        parent::__construct($attributes);
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function registerMediaCollections(): void {
        $this->addMediaCollection('images')
            ->useFallbackUrl('/images/fallback_product_image.png');
    }

    public function registerMediaConversions(Media $media = null): void {
        $this->addMediaConversion('thumb')
            ->width(50)
            ->height(50);
    }

    public function setProductCostAttribute($value) {
        $this->attributes['product_cost'] = ($value * 100);
    }

    public function getProductCostAttribute($value) {
        return ($value / 100);
    }

    public function setProductPriceAttribute($value) {
        $this->attributes['product_price'] = ($value * 100);
    }

    public function getProductPriceAttribute($value) {
        return ($value / 100);
    }
}
