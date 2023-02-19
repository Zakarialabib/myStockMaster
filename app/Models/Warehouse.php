<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Warehouse
 *
 * @property int $id
 * @property string $name
 * @property string|null $city
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $country
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Models\User> $assignedUsers
 * @property-read int|null $assigned_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Models\Product> $products
 * @property-read int|null $products_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Warehouse extends Model
{
    use HasAdvancedFilter;

    /** @var array<string> $orderable */
    public $orderable = [
        'id',
        'name',
        'city',
        'phone',
        'email',
        'country',
        'created_at',
        'updated_at',
    ];

    /** @var array<string> $filterable */
    public $filterable = [
        'id',
        'name',
        'city',
        'phone',
        'email',
        'country',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'phone', 'country', 'city', 'email',
    ];

    /** @return BelongsToMany<User> */
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /** @return BelongsToMany<Product> */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
