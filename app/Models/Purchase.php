<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PurchaseStatus;
use App\Support\HasAdvancedFilter;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string                          $id
 * @property \Illuminate\Support\Carbon      $date
 * @property string                          $reference
 * @property string|null                     $supplier_id
 * @property string|null                     $user_id
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
 * @property PurchaseStatus                  $status
 * @property int|null                        $payment_id
 * @property string|null                     $shipping_status
 * @property string|null                     $document
 * @property string|null                     $note
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string                          $payment_status
 * @property-read CashRegister|null $cashRegister
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PurchaseDetail> $purchaseDetails
 * @property-read int|null $purchase_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PurchasePayment> $purchasePayments
 * @property-read int|null $purchase_payments_count
 * @property-read Supplier|null $supplier
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase searchByReference($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase thisMonth()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereCashRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereDueAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereShippingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereShippingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereTaxPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Purchase extends Model
{
    protected $casts = [
        'date' => 'date',
    ];

    use HasAdvancedFilter;
    use HasUuid;
    use SoftDeletes;

    public const ATTRIBUTES = [
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
        'payment_id',
        'created_at',
        'updated_at',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

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
        'warehouse_id',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_id',
        'status',
        'shipping_status',
        'note',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PurchaseStatus::class,
        ];
    }

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
        return $this->belongsTo(
            related: Supplier::class,
            foreignKey: 'supplier_id',
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
        );
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchase) {
            $prefix = settings()->purchase_prefix;

            $latestPurchase = self::latest()->first();

            if ($latestPurchase) {
                $number = intval(substr($latestPurchase->reference, -3)) + 1;
            } else {
                $number = 1;
            }

            $purchase->reference = $prefix . str_pad(strval($number), 3, '0', STR_PAD_LEFT);
        });
    }

    /** @param mixed $query */
    public function scopePending($query)
    {
        return $query->whereStatus(PurchaseStatus::PENDING);
    }

    /** @param mixed $query */
    public function scopeCompleted($query)
    {
        return $query->whereStatus(PurchaseStatus::COMPLETED);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month);
    }

    /** get shipping amount */
    protected function shippingAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /** get paid amount */
    protected function paidAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /** get total amount */
    protected function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /** get due amount */
    protected function dueAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /** get tax amount */
    protected function taxAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    /** get discount amount */
    protected function discountAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    public function scopeSearchByReference($query, $term)
    {
        return $query->when(! empty($term), function ($query) use ($term) {
            $query->where('reference', 'like', '%' . $term . '%');
        });
    }
}
