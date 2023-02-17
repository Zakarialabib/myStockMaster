<?php

declare(strict_types=1);

namespace App\Models;

use App\Scopes\ProductScope;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property int $category_id
 * @property int|null $warehouse_id
 * @property int|null $brand_id
 * @property string $name
 * @property string|null $code
 * @property string|null $barcode_symbology
 * @property int $quantity
 * @property int $cost
 * @property int $price
 * @property string|null $unit
 * @property int $stock_alert
 * @property int|null $order_tax
 * @property string|null $note
 * @property int|null $status
 * @property int|null $tax_type
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Brand|null $brand
 * @property-read \App\Models\Category $category
 * @property mixed $product_cost
 * @property mixed $product_price
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product stockValue(\Illuminate\Support\Carbon $date)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBarcodeSymbology($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOrderTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStockAlert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTaxType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWarehouseId($value)
 * @property string $uuid
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUuid($value)
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasAdvancedFilter;
    use Notifiable;
    use ProductScope;
    use HasFactory;
    use GetModelByUuid;
    use UuidGenerator;

    /** @var string[] */
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

    /** @var string[] */
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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'uuid',
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
            'code' => Carbon::now()->format('ymd').mt_rand(10000000, 99999999),
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
}
