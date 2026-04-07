<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

/**
 * @property int                             $id
 * @property string                          $name
 * @property string|null                     $city
 * @property string|null                     $address
 * @property string|null                     $phone
 * @property string|null                     $email
 * @property string|null                     $country
 * @property int                             $status
 * @property int|null                        $user_id
 * @property string|null                     $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read UserWarehouse|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $assignedUsers
 * @property-read int|null $assigned_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductWarehouse> $productWarehouse
 * @property-read int|null $product_warehouse_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $products
 * @property-read int|null $products_count
 * @property-read mixed $stock_value
 * @property-read mixed $total_quantity
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 * @property-read int|null $users_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse advancedFilter($data)
 * @method static \Database\Factories\WarehouseFactory                    factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Warehouse extends Model
{
    use HasAdvancedFilter, HasFactory;

    protected const ATTRIBUTES = [
        'id',
        'name',
        'city',
        'phone',
        'email',
        'country',
        'created_at',
        'updated_at',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'city', 'address', 'phone', 'email', 'country',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_warehouse', 'warehouse_id', 'user_id');
    }

    /** @return BelongsToMany<Product> */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_warehouse', 'warehouse_id', 'product_id')
            ->withPivot('price', 'cost', 'qty', 'old_price', 'stock_alert');
    }

    public function productWarehouse()
    {
        return $this->hasMany(ProductWarehouse::class, 'warehouse_id');
    }

    protected function totalQuantity(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->productWarehouse()->sum('qty'),
        );
    }

    protected function stockValue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->productWarehouse()->sum(DB::raw('qty * cost')) / 100,
        );
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class)->using(UserWarehouse::class)
            ->withPivot('user_id', 'warehouse_id');
    }
}
