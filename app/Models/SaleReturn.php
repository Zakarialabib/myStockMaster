<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\SaleReturnStatus;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\SaleReturn
 *
 * @property int $id
 * @property string $date
 * @property string $reference
 * @property int|null $customer_id
 * @property int $tax_percentage
 * @property int $tax_amount
 * @property int $discount_percentage
 * @property int $discount_amount
 * @property int $shipping_amount
 * @property int $total_amount
 * @property int $paid_amount
 * @property int $due_amount
 * @property string $status
 * @property string $payment_status
 * @property string $payment_method
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Models\SaleReturnDetail> $saleReturnDetails
 * @property-read int|null $sale_return_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Models\SaleReturnPayment> $saleReturnPayments
 * @property-read int|null $sale_return_payments_count
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn completed()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn query()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereDueAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereShippingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereTaxPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereUpdatedAt($value)
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereDeletedAt($value)
 * @property int $user_id
 * @property int|null $warehouse_id
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturn whereWarehouseId($value)
 * @mixin \Eloquent
 */
class SaleReturn extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
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
        'payment_method',
        'note',
        'customer_id',
    ];

    public $filterable = [
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
        'payment_method',
        'note',
        'customer_id',
    ];

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
        'payment_method',
        'note',
        'customer_id',
    ];

    protected $casts = [
        'status' => SaleReturnStatus::class,
        'payment_status' => PaymentStatus::class,
    ];

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

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    public function scopeCompleted($query)
    {
        return $query->whereStatus(2);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $number = SaleReturn::max('id') + 1;
            $model->reference = make_reference_id('SLRN', $number);
        });
    }

    /**
     * get shipping amount
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function shippingAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /**
     * get paid amount
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function paidAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /**
     * get total amount
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /**
     * get due amount
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function dueAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /**
     * get tax amount
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function taxAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /**
     * get discount amount
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function discountAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }
}
