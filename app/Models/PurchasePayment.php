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
 * @property string      $purchase_id
 * @property string|null $user_id
 * @property numeric     $amount
 * @property Carbon      $date
 * @property string      $reference
 * @property string      $payment_method
 * @property string|null $note
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Purchase|null $purchase
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment byPurchase()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment wherePurchaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchasePayment whereUserId($value)
 *
 * @mixin \Eloquent
 */
class PurchasePayment extends Model
{
    protected $casts = [
        'date' => 'date',
    ];

    use HasAdvancedFilter;

    public const ATTRIBUTES = [
        'id',
        'purchase_id',
        'payment_id',
        'amount',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    protected $guarded = [];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchasePayment) {
            $prefix = settings()->purchasePayment_prefix;

            $latestPurchasePayment = self::latest()->first();

            if ($latestPurchasePayment) {
                $number = intval(substr($latestPurchasePayment->reference, -3)) + 1;
            } else {
                $number = 1;
            }

            $purchasePayment->reference = $prefix . str_pad(strval($number), 3, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Get ajustement date.
     */
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
    public function scopeByPurchase($query)
    {
        return $query->wherePurchaseId(request()->route('purchase_id'));
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
}
