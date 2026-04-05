<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property int         $sale_return_id
 * @property string|null $user_id
 * @property numeric     $amount
 * @property Carbon      $date
 * @property string      $reference
 * @property string      $payment_method
 * @property string|null $note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SaleReturn $saleReturn
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment bySaleReturn()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereSaleReturnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleReturnPayment whereUserId($value)
 *
 * @mixin \Eloquent
 */
class SaleReturnPayment extends Model
{
    protected $casts = [
        'date' => 'date',
    ];

    use HasAdvancedFilter;

    public const ATTRIBUTES = [
        'id',
        'sale_return_id',
        'amount',
        'payment_id',
        'created_at',
        'updated_at',

    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    protected $guarded = [];

    public function saleReturn(): BelongsTo
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id', 'id');
    }

    /**
     * Get ajustement date.
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
    public function scopeBySaleReturn($query)
    {
        return $query->whereSaleReturnId(request()->route('sale_return_id'));
    }

    /**
     * Interact with the expenses amount
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
}
