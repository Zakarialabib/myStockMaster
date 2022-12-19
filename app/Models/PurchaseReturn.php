<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\PaymentStatus;
use App\Enums\PurchaseReturnStatus;

/**
 * App\Models\PurchaseReturn
 *
 * @property int $id
 * @property string $date
 * @property string $reference
 * @property int|null $supplier_id
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PurchaseReturnDetail[] $purchaseReturnDetails
 * @property-read int|null $purchase_return_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PurchaseReturnPayment[] $purchaseReturnPayments
 * @property-read int|null $purchase_return_payments_count
 * @property-read \App\Models\Supplier|null $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn completed()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn query()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn whereDueAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn whereShippingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn whereTaxPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturn whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PurchaseReturn extends Model
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
        'supplier_id',
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
        'supplier_id',
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
        'supplier_id',
    ];

    /** @return response() */
    protected $casts = [
        'status'         => PurchaseReturnStatus::class,
        'payment_status' => PaymentStatus::class,
    ];

    /** @return HasMany<PurchaseReturnDetail> */
    public function purchaseReturnDetails(): HasMany
    {
        return $this->hasMany(PurchaseReturnDetail::class, 'purchase_return_id', 'id');
    }

    /** @return HasMany<PurchaseReturnPayment> */
    public function purchaseReturnPayments(): HasMany
    {
        return $this->hasMany(PurchaseReturnPayment::class, 'purchase_return_id', 'id');
    }

    /** @return BelongsTo<Supplier> */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
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
            $number = PurchaseReturn::max('id') + 1;
            $model->reference = make_reference_id('PRRN', $number);
        });
    }
}
