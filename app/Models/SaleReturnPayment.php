<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\SaleReturnPayment
 *
 * @property int $id
 * @property int $sale_return_id
 * @property int $amount
 * @property string $date
 * @property string $reference
 * @property string $payment_method
 * @property string|null $note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\SaleReturn $saleReturn
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnPayment advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnPayment bySaleReturn()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnPayment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnPayment whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnPayment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnPayment whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnPayment whereSaleReturnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleReturnPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SaleReturnPayment extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'sale_return_id',
        'amount',
        'payment_method',
        'payment_note',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'sale_return_id',
        'amount',
        'payment_method',
        'payment_note',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    public function saleReturn(): BelongsTo
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id', 'id');
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value * 100;
    }

    public function getAmountAttribute($value)
    {
        return $value / 100;
    }

    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d M, Y');
    }

    public function scopeBySaleReturn($query)
    {
        return $query->whereSaleReturnId(request()->route('sale_return_id'));
    }
}
