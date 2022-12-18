<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Supplier
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string $phone
 * @property string|null $address
 * @property string|null $city
 * @property string|null $country
 * @property string|null $tax_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Purchase|null $purchases
 * @property-read \App\Models\Wallet|null $wallet
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Supplier extends Model
{
    use HasAdvancedFilter;

   /** 
     * @var string[] 
    */
    public $orderable = [
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

   /** 
     * @var string[] 
    */
    public $filterable = [
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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'city',
        'country',
        'address',
    ];

    /** 
     * @return HasOne<Wallet> 
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }
    
    /** 
     * @return HasOne<Purchase> 
     */
    public function purchases(): HasOne
    {
        return $this->HasOne(Purchase::class);
    }
}
