<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\QuotationStatus;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int             $id
 * @property string          $customer_id
 * @property string          $user_id
 * @property int|null        $warehouse_id
 * @property Carbon          $date
 * @property string          $reference
 * @property int             $tax_percentage
 * @property int             $tax_amount
 * @property int             $discount_percentage
 * @property int             $discount_amount
 * @property int             $shipping_amount
 * @property int             $total_amount
 * @property QuotationStatus $status
 * @property string|null     $sent_on
 * @property string|null     $expires_on
 * @property string|null     $note
 * @property string|null     $deleted_at
 * @property Carbon|null     $created_at
 * @property Carbon|null     $updated_at
 * @property-read Customer|null $customer
 * @property-read mixed $due_amount
 * @property-read mixed $paid_amount
 * @property-read \Illuminate\Database\Eloquent\Collection<int, QuotationDetails> $quotationDetails
 * @property-read int|null $quotation_details_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereExpiresOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereSentOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereShippingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereTaxPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quotation whereWarehouseId($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Quotation extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $casts = [
        'date' => 'date',
    ];

    use HasAdvancedFilter;

    public const ATTRIBUTES = [
        'id',
        'date',
        'reference',
        'customer_id',
        'tax_amount',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'status',
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
        'date',
        'reference',
        'customer_id',
        'user_id',
        'warehouse_id',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'status',
        'note',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'status' => QuotationStatus::class,
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\QuotationDetails, $this>
     */
    public function quotationDetails(): HasMany
    {
        return $this->hasMany(QuotationDetails::class, 'quotation_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * Get ajustement date.
     */
    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn (\DateTimeInterface|\Carbon\WeekDay|\Carbon\Month|string|int|float|null $value): string => \Illuminate\Support\Facades\Date::parse($value)->format('d M, Y'),
        );
    }

    #[\Override]
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quotation): void {
            $prefix = settings()->quotation_prefix;

            $latestQuotation = self::query()->latest()->first();

            $number = $latestQuotation ? intval(substr((string) $latestQuotation->reference, -3)) + 1 : 1;

            $quotation->reference = $prefix . str_pad(strval($number), 3, '0', STR_PAD_LEFT);
        });
    }

    /**
     * get shipping amount
     */
    protected function shippingAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /**
     * get paid amount
     */
    protected function paidAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /**
     * get total amount
     */
    protected function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /**
     * get due amount
     */
    protected function dueAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /**
     * get tax amount
     */
    protected function taxAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }

    /**
     * get discount amount
     */
    protected function discountAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): int|float => $value / 100,
        );
    }
}
