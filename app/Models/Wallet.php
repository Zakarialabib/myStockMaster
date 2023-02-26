<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Wallet
 *
 * @property int $id
 * @property string|null $recieved_amount
 * @property string|null $sent_amount
 * @property string|null $balance
 * @property int|null $user_id
 * @property int|null $customer_id
 * @property int|null $supplier_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\Models\Supplier|null $supplier
 * @property-read \App\Models\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereRecievedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereSentAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Wallet extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'name',
        'recieved_amount',
        'sent_amount',
        'balance',
        'customer_id',
        'supplier_id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'name',
        'recieved_amount',
        'sent_amount',
        'balance',
        'customer_id',
        'supplier_id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'recieved_amount',
        'sent_amount',
        'balance',
        'customer_id',
        'supplier_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
