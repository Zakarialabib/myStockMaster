<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SaleStatus;
use App\Scopes\SaleScope;
use App\Support\HasAdvancedFilter;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string                          $id
 * @property \Illuminate\Support\Carbon      $date
 * @property string                          $reference
 * @property string|null                     $customer_id
 * @property string                          $user_id
 * @property int|null                        $warehouse_id
 * @property int|null                        $cash_register_id
 * @property int                             $tax_percentage
 * @property numeric                         $tax_amount
 * @property int                             $discount_percentage
 * @property numeric                         $discount_amount
 * @property numeric                         $shipping_amount
 * @property numeric                         $total_amount
 * @property numeric                         $paid_amount
 * @property numeric                         $due_amount
 * @property int|null                        $payment_id
 * @property SaleStatus                      $status
 * @property string|null                     $shipping_status
 * @property string|null                     $document
 * @property string|null                     $note
 * @property string|null                     $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string                          $payment_status
 * @property-read CashRegister|null $cashRegister
 * @property-read Customer|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SaleDetails> $saleDetails
 * @property-read int|null $sale_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SalePayment> $salePayments
 * @property-read int|null $sale_payments_count
 * @property-read User $user
 * @property-read Warehouse|null $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale salesTotal(\Illuminate\Support\Carbon $date, int $dividedNumber = 100)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale searchByReference($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale thisMonth()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCashRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereDueAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereShippingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereShippingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereTaxPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereWarehouseId($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Sale extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $casts = [
        'date' => 'date',
    ];

    use HasAdvancedFilter;
    use HasUuid;
    use SaleScope;

    public const ATTRIBUTES = [
        'id',
        'date',
        'reference',
        'customer_id',
        'warehouse_id',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'payment_date',
        'paid_amount',
        'due_amount',
        'status',
        'payment_id',
        'shipping_status',
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
        'id',
        'uuid',
        'date',
        'reference',
        'customer_id',
        'user_id',
        'warehouse_id',
        'tax_percentage',
        'tax_amount',
        'payment_date',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'paid_amount',
        'cash_register_id',
        'due_amount',
        'status',
        'payment_id',
        'shipping_status',
        'note',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'status' => SaleStatus::class,
        ];
    }

    #[\Override]
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale): void {
            $prefix = settings('sale_prefix', 'SL');
            $sale->reference = make_reference_id($prefix, self::class);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\SalePayment, $this>
     */
    public function salePayments(): HasMany
    {
        return $this->hasMany(SalePayment::class, 'sale_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            related: Customer::class,
            foreignKey: 'customer_id',
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\CashRegister, $this>
     */
    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Warehouse, $this>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\SaleDetails, $this>
     */
    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetails::class);
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    protected function scopeCompleted(mixed $query)
    {
        return $query->whereStatus(2);
    }

    protected function scopeThisMonth(mixed $query)
    {
        return $query->whereMonth('date', now()->month);
    }

    /**
     * get shipping amount
     */
    protected function shippingAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /**
     * get paid amount
     */
    protected function paidAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /**
     * get total amount
     */
    protected function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /**
     * get due amount
     */
    protected function dueAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /**
     * get tax amount
     */
    protected function taxAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /**
     * get discount amount
     */
    protected function discountAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    protected function scopeSearchByReference(mixed $query, mixed $term)
    {
        return $query->when(filled($term), function ($query) use ($term): void {
            $query->where('reference', 'like', '%' . $term . '%');
        });
    }
}
