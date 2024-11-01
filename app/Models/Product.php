<?php

declare(strict_types=1);

namespace App\Models;

use App\Scopes\ProductScope;
use App\Support\HasAdvancedFilter;
use App\Traits\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasAdvancedFilter;
    use HasFactory;
    use HasUuid;
    use Notifiable;
    use ProductScope;
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
        'status',
        'tax_amount',
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

    // 'slug' => Str::slug($attributes['name'] ?? ''),
    public static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = $product->generateSlug($product->name);
        });
    }

    /**
     * Generate a slug from the product name.
     *
     * @param  string  $name
     * @return string
     */
    public function generateSlug($name)
    {
        return Str::slug($name, '-');
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

    public function priceHistory(): HasMany
    {
        return $this->hasMany(PriceHistory::class);
    }

    public static function ecommerceProducts()
    {
        return static::whereHas('warehouses', static function ($query): void {
            $query->where('is_ecommerce', 1);
        })->with(['warehouses' => static function ($query): void {
            $query->select('product_id', 'qty', 'price', 'old_price', 'is_discount', 'discount_date');
        }]);
    }

    /**
     * Interact with product cost
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
     */
    protected function productPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    /** @return BelongsToMany<Warehouse> */
    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class)
            ->withPivot('qty', 'price', 'cost', 'old_price', 'stock_alert', 'is_discount', 'discount_date', 'is_ecommerce');
    }

    public function getTotalQuantityAttribute(): int|float|null
    {
        return $this->warehouses->sum('pivot.qty');
    }

    public function getAveragePriceAttribute(): int|float|null
    {
        return $this->warehouses->avg('pivot.price');
    }

    public function getAverageCostAttribute(): int|float|null
    {
        return $this->warehouses->avg('pivot.cost');
    }

    // Add scope for stock alerts
    public function scopeBelowStockAlert($query)
    {
        return $query->whereColumn('quantity', '<=', 'stock_alert');
    }

    // Add method to check if product is below stock alert
    public function isBelowStockAlert(): bool
    {
        return $this->quantity <= $this->stock_alert;
    }

    public function scopeSearchByNameOrCode($query, $term)
    {
        return $query->when( ! empty($term), function ($query) use ($term) {
            $query->where('name', 'like', '%'.$term.'%')
                ->orWhere('code', 'like', '%'.$term.'%');
        });
    }
}
