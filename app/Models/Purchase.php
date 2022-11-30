<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Purchase extends Model
{
    use HasAdvancedFilter;

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

    protected $fillable = [
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

    const PaymentPending = '0';

    const PaymentPaid = '1';

    const PaymentPartial = '2';

    const PaymentDue = '3';

    const PurchasePending = '0';

    const PurchaseOrdered = '1';

    const PurchaseCompleted = '2';

    public function purchaseDetails(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id', 'id');
    }

    public function purchasePayments(): HasMany
    {
        return $this->hasMany(PurchasePayment::class, 'purchase_id', 'id');
    }

    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes([
            'reference' => 'PR-' . Carbon::now()->format('Ymd') . '-' . Str::random(4),
        ], true);
        parent::__construct($attributes);
    }

    public function scopeCompleted($query)
    {
        return $query->whereStatus('Completed');
    }

    public function getShippingAmountAttribute($value)
    {
        return $value / 100;
    }

    public function getPaidAmountAttribute($value)
    {
        return $value / 100;
    }

    public function getTotalAmountAttribute($value)
    {
        return $value / 100;
    }

    public function getDueAmountAttribute($value)
    {
        return $value / 100;
    }

    public function getTaxAmountAttribute($value)
    {
        return $value / 100;
    }

    public function getDiscountAmountAttribute($value)
    {
        return $value / 100;
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
}
