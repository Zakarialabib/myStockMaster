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
 * @property int         $sale_return_id
 * @property string|null $user_id
 * @property numeric     $amount
 * @property Carbon      $date
 * @property string      $reference
 * @property string      $payment_method
 * @property string|null $note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SaleReturn $saleReturn
 * @property-read User|null $user
 * @property-read CashRegister|null $cashRegister
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment bySaleReturn()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereSaleReturnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereUserId($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class SaleReturnPayment extends Model
{
    use HasAdvancedFilter;
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    public const ATTRIBUTES = [
        'id',
        'sale_return_id',
        'amount',
        'payment_id',
        'created_at',
        'updated_at',

    ];

    public array $orderable = self::ATTRIBUTES;

    public array $filterable = self::ATTRIBUTES;

    protected $guarded = [];

    /**
     * @return BelongsTo<SaleReturn, $this>
     */
    public function saleReturn(): BelongsTo
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id', 'id');
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
    protected function scopeBySaleReturn(mixed $query)
    {
        return $query->whereSaleReturnId(request()->route('sale_return_id'));
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
