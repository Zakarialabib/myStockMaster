<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
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
 * @property-read \App\Models\Customer|null $customer
 * @property-read mixed $due_amount
 * @property-read mixed $paid_amount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\QuotationDetails[] $quotationDetails
 * @property-read int|null $quotation_details_count
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
 * @mixin \Eloquent
 */
class Quotation extends Model
{
    use HasAdvancedFilter;

    /** @var string[] */
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

    /** @var string[] */
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

    /** @return HasMany<QuotationDetails> */
    public function quotationDetails(): HasMany
    {
        return $this->hasMany(QuotationDetails::class, 'quotation_id', 'id');
    }

    /** @return BelongsTo<Customer> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d M, Y');
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getShippingAmountAttribute($value)
    {
        return $value / 100;
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getPaidAmountAttribute($value)
    {
        return $value / 100;
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getTotalAmountAttribute($value)
    {
        return $value / 100;
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getDueAmountAttribute($value)
    {
        return $value / 100;
    }

    /**
     * @param mixed $value
     * @return int|float
     */
    public function getTaxAmountAttribute($value)
    {
        return $value / 100;
    }

     /**
      * @param mixed $value
      * @return int|float
      */
    public function getDiscountAmountAttribute($value)
    {
        return $value / 100;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $number = Quotation::max('id') + 1;
            $model->reference = make_reference_id('QT', $number);
        });
    }
}
