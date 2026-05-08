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
use Override;

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
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Purchase extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $casts = [
        'date' => 'date',
        'status' => PurchaseStatus::class,
        'payment_status' => \App\Enums\PaymentStatus::class,
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
    #[Override]
    protected function casts(): array
    {
        return [
            'status' => PurchaseStatus::class,
        ];
    }

    /**
     * @return HasMany<PurchaseDetail, $this>
     */
    public function purchaseDetails(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id', 'id');
    }

    /**
     * @return HasMany<PurchasePayment, $this>
     */
    public function purchasePayments(): HasMany
    {
        return $this->hasMany(PurchasePayment::class, 'purchase_id', 'id');
    }

    /**
     * @return BelongsTo<Supplier, $this>
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(
            related: Supplier::class,
            foreignKey: 'supplier_id',
        );
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
        );
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

        static::creating(function ($purchase): void {
            $prefix = settings('purchase_prefix', 'PR');
            $purchase->reference = make_reference_id($prefix, self::class);
        });
    }

    protected function scopePending(mixed $query)
    {
        return $query->whereStatus(PurchaseStatus::PENDING);
    }

    protected function scopeCompleted(mixed $query)
    {
        return $query->whereStatus(PurchaseStatus::COMPLETED);
    }

    protected function scopeThisMonth(mixed $query)
    {
        return $query->whereMonth('date', now()->month);
    }

    /** get shipping amount */
    protected function shippingAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /** get paid amount */
    protected function paidAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /** get total amount */
    protected function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /** get due amount */
    protected function dueAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /** get tax amount */
    protected function taxAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /** get discount amount */
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
