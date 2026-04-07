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
 * @property string|null $sale_id
 * @property string|null $user_id
 * @property int|null    $cash_register_id
 * @property numeric     $amount
 * @property Carbon      $date
 * @property string      $reference
 * @property string      $payment_method
 * @property string|null $note
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Sale|null $sale
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment bySale()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment whereCashRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalePayment whereUserId($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class SalePayment extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use HasAdvancedFilter;

    public const ATTRIBUTES = [
        'id',
        'sale_id',
        'payment_id',
        'reference',
        'amount',
        'created_at',
        'updated_at',

    ];

    public array $orderable = self::ATTRIBUTES;

    public array $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'reference',
        'amount',
        'payment_method',
        'note',
        'sale_id',
        'payment_id',
        'user_id',
        'cash_register_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Sale, $this>
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    /**
     * Get ajustement date.
     */
    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn (\DateTimeInterface|\Carbon\WeekDay|\Carbon\Month|string|int|float|null $value): string => \Illuminate\Support\Facades\Date::parse($value)->format('d M, Y'),
        );
    }

    #[\Override]
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($salePayment): void {
            $prefix = settings()->salePayment_prefix;

            $latestSalePayment = self::query()->latest()->first();

            $number = $latestSalePayment ? intval(substr($latestSalePayment->reference, -3)) + 1 : 1;

            $salePayment->reference = $prefix . str_pad(strval($number), 3, '0', STR_PAD_LEFT);
        });
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    protected function scopeBySale(mixed $query)
    {
        return $query->whereSaleId(request()->route('sale_id'));
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
    #[\Override]
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
