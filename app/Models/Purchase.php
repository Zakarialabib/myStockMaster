<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;
use App\Support\HasAdvancedFilter;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Purchase
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
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Models\PurchaseDetail> $purchaseDetails
 * @property-read int|null $purchase_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Models\PurchasePayment> $purchasePayments
 * @property-read int|null $purchase_payments_count
 * @property-read \App\Models\Supplier|null $supplier
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase completed()
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase query()
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereDueAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereShippingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereTaxPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereUpdatedAt($value)
 *
 * @property string $uuid
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Purchase whereUuid($value)
 *
 * @mixin \Eloquent
 */
class Purchase extends Model
{
    use HasAdvancedFilter;
    use GetModelByUuid;
    use UuidGenerator;

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
        'created_at',
        'updated_at',
    ];

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
        'created_at',
        'updated_at',
    ];

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
        'supplier_id',
        'user_id',
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
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'status' => PurchaseStatus::class,
        'payment_status' => PaymentStatus::class,
    ];

    public function purchaseDetails(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id', 'id');
    }

    public function purchasePayments(): HasMany
    {
        return $this->hasMany(PurchasePayment::class, 'purchase_id', 'id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    /** @param mixed $query */
    public function scopeCompleted($query)
    {
        return $query->whereStatus(2);
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
