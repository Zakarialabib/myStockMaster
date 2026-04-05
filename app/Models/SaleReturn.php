<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SaleReturnStatus;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @mixin \Eloquent
 */
class SaleReturn extends Model
{
    protected $casts = [
        'date' => 'date',
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
        'payment_id',
        'customer_id',

    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

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
        'payment_id',
        'note',
        'customer_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => SaleReturnStatus::class,
        ];
    }

    /** @return HasMany<SaleReturnDetail> */
    public function saleReturnDetails(): HasMany
    {
        return $this->hasMany(SaleReturnDetail::class, 'sale_return_id', 'id');
    }

    /** @return HasMany<SaleReturnPayment> */
    public function saleReturnPayments(): HasMany
    {
        return $this->hasMany(SaleReturnPayment::class, 'sale_return_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($saleReturn) {
            $prefix = settings()->saleReturn_prefix;

            $latestSaleReturn = self::latest()->first();

            if ($latestSaleReturn) {
                $number = intval(substr($latestSaleReturn->reference, -3)) + 1;
            } else {
                $number = 1;
            }

            $saleReturn->reference = $prefix . str_pad(strval($number), 3, '0', STR_PAD_LEFT);
        });
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    public function scopeCompleted($query)
    {
        return $query->whereStatus(2);
    }

    /**
     * get shipping amount
     */
    protected function shippingAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /**
     * get paid amount
     */
    protected function paidAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /**
     * get total amount
     */
    protected function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /**
     * get due amount
     */
    protected function dueAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /**
     * get tax amount
     */
    protected function taxAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /**
     * get discount amount
     */
    protected function discountAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }
}
