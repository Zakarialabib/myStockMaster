<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quotation
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
 * @property string $status
 * @property string|null $note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read \App\Models\Customer|null $customer
 * @property-read mixed $due_amount
 * @property-read mixed $paid_amount
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Models\QuotationDetails> $quotationDetails
 * @property-read int|null $quotation_details_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation whereShippingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation whereTaxPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation whereUpdatedAt($value)
 *
 * @property string|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Quotation whereDeletedAt($value)
 *
 * @mixin \Eloquent
 */
class Quotation extends Model
{
    use HasAdvancedFilter;

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
        'status',
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
        'status',
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
        'date',
        'reference',
        'customer_id',
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

    public function quotationDetails(): HasMany
    {
        return $this->hasMany(QuotationDetails::class, 'quotation_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $number = Quotation::max('id') + 1;
            $model->reference = make_reference_id('QT', $number);
        });
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
