<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class PurchasePayment extends Model
{
    use HasAdvancedFilter;

    public const ATTRIBUTES = [
        'id',
        'purchase_id',
        'payment_method',
        'amount',
        'payment_date',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public $orderable = self::ATTRIBUTES;
    public $filterable = self::ATTRIBUTES;

    protected $guarded = [];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchasePayment) {
            $prefix = settings()->purchasePayment_prefix;

            $latestPurchasePayment = self::latest()->first();

            if ($latestPurchasePayment) {
                $number = intval(substr($latestPurchasePayment->reference, -3)) + 1;
            } else {
                $number = 1;
            }

            $purchasePayment->reference = $prefix.str_pad(strval($number), 3, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Get ajustement date.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function date(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d M, Y'),
        );
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    public function scopeByPurchase($query)
    {
        return $query->wherePurchaseId(request()->route('purchase_id'));
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
}
