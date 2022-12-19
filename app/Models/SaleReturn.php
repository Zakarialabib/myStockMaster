<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\PaymentStatus;
use App\Enums\SaleReturnStatus;

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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SaleReturnDetail[] $saleReturnDetails
 * @property-read int|null $sale_return_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SaleReturnPayment[] $saleReturnPayments
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
 * @mixin \Eloquent
 */
class SaleReturn extends Model
{
    use HasAdvancedFilter;

    /** @var string[] */
    public $orderable = [
        'id',
        'date',
        'reference',
        'supplier_id',
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

    /** @var string[] */
    public $filterable = [
        'id',
        'date',
        'reference',
        'supplier_id',
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
        'supplier_id',
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

    /** @return response() */
    protected $casts = [
        'status'         => SaleReturnStatus::class,
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

    /** @return BelongsTo<Customer> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * @param mixed $query
     * @return mixed
     */
    public function scopeCompleted($query)
    {
        return $query->whereStatus(2);
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getShippingAmountAttribute($value)
    {
        return $value / 100;
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getPaidAmountAttribute($value)
    {
        return $value / 100;
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getTotalAmountAttribute($value)
    {
        return $value / 100;
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getDueAmountAttribute($value)
    {
        return $value / 100;
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getTaxAmountAttribute($value)
    {
        return $value / 100;
    }

     /**
      * @param mixed $value
      * @return int|float
      */
    public function getDiscountAmountAttribute($value)
    {
        return $value / 100;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $number = SaleReturn::max('id') + 1;
            $model->reference = make_reference_id('SLRN', $number);
        });
    }
}
