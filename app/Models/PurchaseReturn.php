<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PurchaseReturnStatus;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                             $id
 * @property \Illuminate\Support\Carbon      $date
 * @property string                          $reference
 * @property string                          $supplier_id
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
 * @property PurchaseReturnStatus            $status
 * @property int|null                        $payment_id
 * @property string|null                     $note
 * @property string|null                     $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read CashRegister|null $cashRegister
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PurchaseReturnDetail> $purchaseReturnDetails
 * @property-read int|null $purchase_return_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PurchaseReturnPayment> $purchaseReturnPayments
 * @property-read int|null $purchase_return_payments_count
 * @property-read Supplier|null $supplier
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereCashRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereDueAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereShippingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereTaxPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn whereWarehouseId($value)
 *
 * @mixin \Eloquent
 */
class PurchaseReturn extends Model
{
    protected $casts = [
        'date' => 'date',
    ];

    use HasAdvancedFilter;

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
        'supplier_id',
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
        'status',
        'payment_id',
        'note',
        'supplier_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PurchaseReturnStatus::class,
        ];
    }

    /** @return HasMany<PurchaseReturnDetail> */
    public function purchaseReturnDetails(): HasMany
    {
        return $this->hasMany(PurchaseReturnDetail::class, 'purchase_return_id', 'id');
    }

    public function syncTotals(): void
    {
        $details = $this->purchaseReturnDetails;
        $payments = $this->purchaseReturnPayments;

        $subTotal = $details->sum(fn ($detail) => $detail->getRawOriginal('sub_total'));
        $taxAmount = ($subTotal * $this->tax_percentage) / 100;
        $discountAmount = ($subTotal * $this->discount_percentage) / 100;
        $shippingAmount = $this->getRawOriginal('shipping_amount');

        $totalAmount = $subTotal + $taxAmount - $discountAmount + $shippingAmount;
        $paidAmount = $payments->sum(fn ($payment) => $payment->getRawOriginal('amount'));
        $dueAmount = $totalAmount - $paidAmount;

        $this->updateQuietly([
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
        ]);
    }

    public function purchaseReturnPayments(): HasMany
    {
        return $this->hasMany(PurchaseReturnPayment::class, 'purchase_return_id', 'id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(
            related: Supplier::class,
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

        static::creating(function ($purchaseReturn) {
            $prefix = settings()->purchaseReturn_prefix;

            $latestPurchaseReturn = self::latest()->first();

            if ($latestPurchaseReturn) {
                $number = intval(substr($latestPurchaseReturn->reference, -3)) + 1;
            } else {
                $number = 1;
            }

            $purchaseReturn->reference = $prefix . str_pad(strval($number), 3, '0', STR_PAD_LEFT);
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
     * @param mixed $value
     *
     * @return int|float
     */
    protected function discountAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
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

    protected function taxAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }
}
