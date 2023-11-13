<?php

declare(strict_types=1);

namespace App\Models;

use App\Scopes\ProductScope;
use App\Support\HasAdvancedFilter;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasAdvancedFilter;
    use Notifiable;
    use ProductScope;
    use HasFactory;
    use GetModelByUuid;
    use UuidGenerator;
    use SoftDeletes;

    public const ATTRIBUTES = [
        'id',
        'category_id',
        'name',
        'code',
        'created_at',
    ];

    public $orderable = self::ATTRIBUTES;
    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'featured',
        'uuid',
        'name',
        'code',
        'barcode_symbology',
        'quantity',
        'cost',
        'price',
        'unit',
        'stock_alert',
        'status',
        'order_tax',
        'tax_type',
        'note',
    ];

    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes([

            'code' => Carbon::now()->format('Y-m-d').mt_rand(10000000, 99999999),

        ], true);
        parent::__construct($attributes);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function movements(): MorphMany
    {
        return $this->morphMany(Movement::class, 'movable');
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class)->using(ProductWarehouse::class)
            ->withPivot('price', 'qty', 'cost');
    }

    public function priceHistory()
    {
        return $this->hasMany(PriceHistory::class);
    }

    /**
     * Interact with product cost
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function productCost(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    /**
     * Interact with product price
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function productPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    public function getTotalQuantityAttribute()
    {
        return $this->warehouses->sum('pivot.qty');
    }

    public function getAveragePriceAttribute()
    {
        return $this->warehouses->avg('pivot.price');
    }

    public function getAverageCostAttribute()
    {
        return $this->warehouses->avg('pivot.cost');
    }
}
