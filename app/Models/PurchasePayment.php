<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Override;

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
 * @property-read User|null $user
 * @property-read CashRegister|null $cashRegister
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
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class PurchasePayment extends Model
{
    use HasAdvancedFilter;
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    public const ATTRIBUTES = [
        'id',
        'purchase_id',
        'payment_id',
        'amount',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public array $orderable = self::ATTRIBUTES;

    public array $filterable = self::ATTRIBUTES;

    protected $guarded = [];

    /**
     * @return BelongsTo<Purchase, $this>
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo<CashRegister, $this>
     */
    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id', 'id');
    }

    #[Override]
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchasePayment): void {
            $prefix = settings()->purchasePayment_prefix;

            $latestPurchasePayment = self::query()->latest()->first();

            $number = $latestPurchasePayment ? intval(substr($latestPurchasePayment->reference, -3)) + 1 : 1;

            $purchasePayment->reference = $prefix . str_pad(strval($number), 3, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Get ajustement date.
     */
    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn (DateTimeInterface|\Carbon\WeekDay|\Carbon\Month|string|int|float|null $value): string => \Illuminate\Support\Facades\Date::parse($value)->format('d M, Y'),
        );
    }

    /**
     * @return mixed
     */
    protected function scopeByPurchase(mixed $query)
    {
        return $query->wherePurchaseId(request()->route('purchase_id'));
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
            set: fn ($value): int|float => $value * 100,
        );
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
