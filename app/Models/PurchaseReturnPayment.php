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
 * @property-read User|null $user
 * @property-read CashRegister|null $cashRegister
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
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class PurchaseReturnPayment extends Model
{
    use HasAdvancedFilter;
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public array $orderable = self::ATTRIBUTES;

    public array $filterable = self::ATTRIBUTES;

    protected $guarded = [];

    #[Override]
    protected static function boot()
    {
        parent::boot();

        static::created(function ($payment): void {
            $payment->purchaseReturn->syncTotals();
        });

        static::deleted(function ($payment): void {
            $payment->purchaseReturn->syncTotals();
        });
    }

    /**
     * @return BelongsTo<PurchaseReturn, $this>
     */
    public function purchaseReturn(): BelongsTo
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id', 'id');
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

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn (DateTimeInterface|\Carbon\WeekDay|\Carbon\Month|string|int|float|null $value): string => \Illuminate\Support\Facades\Date::parse($value)->format('d M, Y'),
        );
    }

    /**
     * @return mixed
     */
    protected function scopeByPurchaseReturn(mixed $query)
    {
        return $query->wherePurchaseReturnId(request()->route('purchase_return_id'));
    }

    /**
     * Interact with the expenses amount
     */
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
