<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SaleReturnStatus;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

/**
 * @property int                             $id
 * @property string                          $customer_id
 * @property string                          $user_id
 * @property int|null                        $warehouse_id
 * @property int|null                        $cash_register_id
 * @property \Illuminate\Support\Carbon      $date
 * @property string                          $reference
 * @property int                             $tax_percentage
 * @property numeric                         $tax_amount
 * @property int                             $discount_percentage
 * @property numeric                         $discount_amount
 * @property numeric                         $shipping_amount
 * @property numeric                         $total_amount
 * @property numeric                         $paid_amount
 * @property numeric                         $due_amount
 * @property SaleReturnStatus                $status
 * @property int|null                        $payment_id
 * @property string|null                     $note
 * @property string|null                     $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read CashRegister|null $cashRegister
 * @property-read Customer|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SaleReturnDetail> $saleReturnDetails
 * @property-read int|null $sale_return_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SaleReturnPayment> $saleReturnPayments
 * @property-read int|null $sale_return_payments_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereCashRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereDueAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereShippingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereTaxPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturn whereWarehouseId($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class SaleReturn extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $casts = [
        'date' => 'date',
        'status' => SaleReturnStatus::class,
        'payment_status' => \App\Enums\PaymentStatus::class,
    ];

    use HasAdvancedFilter;

    public const ATTRIBUTES = [
        'id',
        'date',
        'reference',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'paid_amount',
        'due_amount',
        'status',
        'payment_status',
        'payment_id',
        'customer_id',

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
        'customer_id',
        'user_id',
        'warehouse_id',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'paid_amount',
        'due_amount',
        'status',
        'payment_status',
        'payment_id',
        'note',
        'customer_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'status' => SaleReturnStatus::class,
            'payment_status' => \App\Enums\PaymentStatus::class,
        ];
    }

    /** @return HasMany<SaleReturnDetail, $this> */
    public function saleReturnDetails(): HasMany
    {
        return $this->hasMany(SaleReturnDetail::class, 'sale_return_id', 'id');
    }

    /** @return HasMany<SaleReturnPayment, $this> */
    public function saleReturnPayments(): HasMany
    {
        return $this->hasMany(SaleReturnPayment::class, 'sale_return_id', 'id');
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * @return BelongsTo<CashRegister, $this>
     */
    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id', 'id');
    }

    #[Override]
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($saleReturn): void {
            $prefix = settings('saleReturn_prefix', 'SLR');
            $saleReturn->reference = make_reference_id($prefix, self::class);
        });
    }

    /**
     * @return mixed
     */
    protected function scopeCompleted(mixed $query)
    {
        return $query->whereStatus(2);
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
}
