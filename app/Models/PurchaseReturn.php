<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PurchaseReturnStatus;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseReturn extends Model
{
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
            foreignKey: 'supplier_id',
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
