<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property int         $purchase_return_id
 * @property string|null $user_id
 * @property int         $amount
 * @property Carbon      $date
 * @property string      $reference
 * @property string      $payment_method
 * @property string|null $note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read PurchaseReturn $purchaseReturn
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnPayment advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnPayment byPurchaseReturn()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnPayment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnPayment whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnPayment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnPayment wherePurchaseReturnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnPayment whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnPayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnPayment whereUserId($value)
 *
 * @mixin \Eloquent
 */
class PurchaseReturnPayment extends Model
{
    protected $casts = [
        'date' => 'date',
    ];

    use HasAdvancedFilter;

    public const ATTRIBUTES = [
        'id',
        'date',
        'reference',
        'amount',
        'note',
        'purchase_return_id',
        'payment_id',
        'created_at',
        'updated_at',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($payment) {
            $payment->purchaseReturn->syncTotals();
        });

        static::deleted(function ($payment) {
            $payment->purchaseReturn->syncTotals();
        });
    }

    public function purchaseReturn(): BelongsTo
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id', 'id');
    }

    public function date(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d M, Y'),
        );
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    public function scopeByPurchaseReturn($query)
    {
        return $query->wherePurchaseReturnId(request()->route('purchase_return_id'));
    }

    /**
     * Interact with the expenses amount
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
}
