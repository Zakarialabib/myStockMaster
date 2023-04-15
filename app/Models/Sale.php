<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Scopes\SaleScope;
use App\Support\HasAdvancedFilter;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Sale
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
 * @property string|null $shipping_status
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Models\SaleDetails> $saleDetails
 * @property-read int|null $sale_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Models\SalePayment> $salePayments
 * @property-read int|null $sale_payments_count
 * @method static \Illuminate\Database\Eloquent\Builder|Sale advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale completed()
 * @method static \Illuminate\Database\Eloquent\Builder|Sale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sale salesTotal(\Illuminate\Support\Carbon $date, int $dividedNumber = 100)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereDueAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereShippingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereShippingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereTaxPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereUpdatedAt($value)
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereUuid($value)
 * @property int $user_id
 * @property int|null $warehouse_id
 * @property string|null $payment_date
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereWarehouseId($value)
 * @property string|null $document
 * @method static \Illuminate\Database\Eloquent\Builder|Sale thisMonth()
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereDocument($value)
 * @mixin \Eloquent
 */
class Sale extends Model
{
    use HasAdvancedFilter;
    use SaleScope;
    use UuidGenerator;
    use GetModelByUuid;

    public $orderable = [
        'id',
        'date',
        'reference',
        'customer_id',
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
        'payment_status',
        'payment_method',
        'shipping_status',
        'note',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'date',
        'reference',
        'customer_id',
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
        'payment_status',
        'payment_method',
        'shipping_status',
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
        'customer_id',
        'user_id',
        'warehouse_id',
        'user_id',
        'tax_percentage',
        'tax_amount',
        'payment_date',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'paid_amount',
        'due_amount',
        'status',
        'payment_status',
        'payment_method',
        'shipping_status',
        'note',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'status'         => SaleStatus::class,
        'payment_status' => PaymentStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            $prefix = settings()->sale_prefix;
    
            $latestSale = self::latest()->first();
    
            if ($latestSale) {
                $number = intval(substr($latestSale->reference, -3)) + 1;
            } else {
                $number = 1;
            }
    
            $sale->reference = $prefix . str_pad(strval($number), 3, '0', STR_PAD_LEFT);
        });
    }

    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetails::class);
    }

    public function salePayments(): HasMany
    {
        return $this->hasMany(SalePayment::class, 'sale_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            related: Customer::class,
            foreignKey: 'customer_id',
        );
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

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month);
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
