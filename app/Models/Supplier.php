<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string                          $id
 * @property string                          $name
 * @property string|null                     $phone
 * @property string|null                     $email
 * @property string|null                     $city
 * @property string|null                     $country
 * @property string|null                     $address
 * @property string|null                     $tax_number
 * @property int                             $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Purchase|null $purchases
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier advancedFilter($data)
 * @method static \Database\Factories\SupplierFactory                    factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier searchByName($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Supplier extends Model
{
    use HasAdvancedFilter;
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    protected const ATTRIBUTES = [
        'id',
        'name',
        'email',
        'phone',
        'city',
        'country',
        'address',
        'created_at',
        'tax_number',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', 'name', 'email', 'phone', 'address', 'city', 'country', 'tax_number',
    ];

    /** @return HasOne<Purchase> */
    public function purchases(): HasOne
    {
        return $this->hasOne(Purchase::class);
    }

    public function scopeSearchByName($query, $name)
    {
        return $query->when(! empty($name), function ($query) use ($name) {
            return $query->where('name', 'like', '%' . $name . '%');
        });
    }

    private function supplierSum($column, $model)
    {
        return $model::where('supplier_id', $this->id)->sum($column);
    }
}
