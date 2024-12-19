<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Scopes\SaleScope;
use App\Support\HasAdvancedFilter;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasAdvancedFilter;
    use SaleScope;
    use HasUuid;

    public const ATTRIBUTES = [
        'id',
        'date',
        'reference',
        'customer_id',
        'warehouse_id',
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
        'payment_id',
        'shipping_status',
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
        'id', 'uuid', 'date', 'reference', 'customer_id', 'user_id', 'warehouse_id',
        'tax_percentage', 'tax_amount', 'payment_date', 'discount_percentage', 'discount_amount',
        'shipping_amount', 'total_amount', 'paid_amount', 'cash_register_id', 'due_amount',
        'status',  'payment_id', 'shipping_status', 'note',
    ];

    protected $casts = [
        'status' => SaleStatus::class,
        // 'payment_status' => PaymentStatus::class,

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

            $sale->reference = $prefix.str_pad(strval($number), 3, '0', STR_PAD_LEFT);
        });
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
        );
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetails::class);
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
     * @return Attribute
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
     * @return Attribute
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
     * @return Attribute
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
     * @return Attribute
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
     * @return Attribute
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
     * @return Attribute
     */
    protected function discountAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
        );
    }

    public function scopeSearchByReference($query, $term)
    {
        return $query->when(!empty($term), function ($query) use ($term) {
            $query->where('reference', 'like', '%' . $term . '%');
        });
    }
}
