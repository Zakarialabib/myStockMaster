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

/**
 * @property string                          $id
 * @property int                             $category_id
 * @property int|null                        $brand_id
 * @property string                          $name
 * @property string|null                     $code
 * @property string                          $slug
 * @property string|null                     $barcode_symbology
 * @property int                             $quantity
 * @property string|null                     $image
 * @property string|null                     $gallery
 * @property string|null                     $unit
 * @property int                             $tax_amount
 * @property string|null                     $description
 * @property bool                            $status
 * @property int                             $tax_type
 * @property string|null                     $embeded_video
 * @property array<array-key, mixed>|null    $options
 * @property string|null                     $usage
 * @property bool                            $featured
 * @property bool                            $best
 * @property bool                            $hot
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null                     $seasonality
 * @property int                             $availability
 * @property numeric                         $price
 * @property numeric                         $cost
 * @property-read mixed $average_cost
 * @property-read mixed $average_old_price
 * @property-read mixed $average_price
 * @property-read Brand|null $brand
 * @property-read Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Movement> $movements
 * @property-read int|null $movements_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PriceHistory> $priceHistory
 * @property-read int|null $price_history_count
 * @property mixed $product_cost
 * @property mixed $product_price
 * @property-read mixed $total_quantity
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Warehouse> $warehouses
 * @property-read int|null $warehouses_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product belowStockAlert()
 * @method static \Database\Factories\ProductFactory                    factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product productsByCategory($category_id)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product searchByNameOrCode($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereAvailability($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBarcodeSymbology($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereEmbededVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereGallery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereHot($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSeasonality($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereTaxType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUsage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withoutTrashed()
 *
 * @mixin \Eloquent
 */
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

    public array $orderable = self::ATTRIBUTES;

    public array $filterable = self::ATTRIBUTES;

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
    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'status' => 'boolean',
            'hot' => 'boolean',
            'best' => 'boolean',
            'options' => 'array',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
            if (empty($product->code)) {
                $product->code = Carbon::now()->format('Y-m-d') . mt_rand(10000000, 99999999);
            }
        });
    }

    /**
     * Generate a slug from the product name.
     *
     * @param string $name
     *
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
            get: function ($value) {
                if (array_key_exists('pivot_cost', $this->attributes)) {
                    return $this->attributes['pivot_cost'] / 100;
                }

                return $value / 100;
            },
            set: fn ($value) => $value * 100,
        );
    }

    /** Interact with product price */
    protected function productPrice(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (array_key_exists('pivot_price', $this->attributes)) {
                    return $this->attributes['pivot_price'] / 100;
                }

                return $value / 100;
            },
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
                if (array_key_exists('total_qty', $this->attributes)) {
                    return $this->attributes['total_qty'] !== null ? (float) $this->attributes['total_qty'] : null;
                }

                $sum = $this->warehouses()->sum('qty');

                return $sum !== null ? (float) $sum : null;
            }
        );
    }

    protected function averagePrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (array_key_exists('avg_price', $this->attributes)) {
                    return $this->attributes['avg_price'] !== null ? (float) $this->attributes['avg_price'] : null;
                }

                $avg = $this->warehouses()->avg('price');

                return $avg !== null ? (float) $avg : null;
            }
        );
    }

    protected function averageCost(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (array_key_exists('avg_cost', $this->attributes)) {
                    return $this->attributes['avg_cost'] !== null ? (float) $this->attributes['avg_cost'] : null;
                }

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
        return $query->when(! empty($term), function ($query) use ($term) {
            $query->where('name', 'like', '%' . $term . '%')
                ->orWhere('code', 'like', '%' . $term . '%');
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
