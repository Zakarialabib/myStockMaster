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
        'id',
        'name',
        'code',
        'barcode_symbology',
        'seasonality',
        'availability',
        'unit',
        'status',
        'tax_amount',
        'tax_type',
        'note',
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
            'featured' => 'boolean',
            'status'   => 'boolean',
            'hot'      => 'boolean',
            'best'     => 'boolean',
            'options'  => 'array',
        ];
    }

    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes([
            'code' => Carbon::now()->format('Y-m-d').mt_rand(10000000, 99999999),
        ], true);
        parent::__construct($attributes);
    }

    // 'slug' => Str::slug($attributes['name'] ?? ''),
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
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

    /** Interact with product cost */
    protected function productCost(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    /** Interact with product price */
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

    protected function totalQuantity(): Attribute
    {
        return Attribute::make(
            get: function () {
                $sum = $this->warehouses()->sum('qty');
                return $sum !== null ? (float) $sum : null;
            }
        );
    }

    protected function averagePrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                $avg = $this->warehouses()->avg('price');
                return $avg !== null ? (float) $avg : null;
            }
        );
    }

    protected function averageCost(): Attribute
    {
        return Attribute::make(
            get: function () {
                $avg = $this->warehouses()->avg('cost');
                return $avg !== null ? (float) $avg : null;
            }
        );
    }

    protected function averageOldPrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                $avg = $this->warehouses()->avg('old_price');
                return $avg !== null ? (float) $avg : null;
            }
        );
    }

    // Add scope for stock alerts
    public function scopeBelowStockAlert($query)
    {
        return $query->whereColumn('quantity', '<=', 'stock_alert');
    }

    // Add method to check if product is below stock alert
    public function isBelowStockAlert(): bool
    {
        return $this->total_quantity <= ($this->stock_alert ?? 0);
    }

    public function scopeSearchByNameOrCode($query, $term)
    {
        return $query->when( ! empty($term), function ($query) use ($term) {
            $query->where('name', 'like', '%'.$term.'%')
                ->orWhere('code', 'like', '%'.$term.'%');
        });
    }

    public function isAvailable()
    {
        return $this->total_quantity > 0;
    }

    public function getDiscountedPrice()
    {
        $averagePrice = $this->average_price;
        $averageOldPrice = $this->average_old_price;

        if ($averageOldPrice > 0 && $averagePrice > 0) {
            // If there's an old price, return the current price (which is already discounted)
            return $averagePrice;
        }

        return $averagePrice ?? 0;
    }
}
