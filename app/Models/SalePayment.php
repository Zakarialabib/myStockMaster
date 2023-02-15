<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

/**
 * App\Models\SalePayment
 *
 * @property int $id
 * @property int $sale_id
 * @property int $amount
 * @property string $date
 * @property string $reference
 * @property string $payment_method
 * @property string|null $note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Sale $sale
 * @method static \Illuminate\Database\Eloquent\Builder|SalePayment advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|SalePayment bySale()
 * @method static \Illuminate\Database\Eloquent\Builder|SalePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SalePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SalePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|SalePayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalePayment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalePayment whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalePayment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalePayment whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalePayment whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalePayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SalePayment extends Model
{
    use HasAdvancedFilter;

    /** @var string[] */
    public $orderable = [
        'id',
        'sale_id',
        'payment_method',
        'reference',
        'amount',
        'created_at',
        'updated_at',
    ];

    /** @var string[] */
    public $filterable = [
        'id',
        'sale_id',
        'payment_method',
        'reference',
        'amount',
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
        'amount',
        'note',
        'sale_id',
        'payment_method',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    /**
     * Interact with the expenses amount
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    /**
     * Get ajustement date.
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
     * @return mixed
     */
    public function scopeBySale($query)
    {
        return $query->whereSaleId(request()->route('sale_id'));
    }
}
