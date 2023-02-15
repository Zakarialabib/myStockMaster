<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\PurchasePayment
 *
 * @property int $id
 * @property int $purchase_id
 * @property int $amount
 * @property string $date
 * @property string $reference
 * @property string $payment_method
 * @property string|null $note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Purchase $purchase
 * @method static \Illuminate\Database\Eloquent\Builder|PurchasePayment advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchasePayment byPurchase()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchasePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchasePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchasePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchasePayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchasePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchasePayment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchasePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchasePayment whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchasePayment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchasePayment wherePurchaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchasePayment whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchasePayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PurchasePayment extends Model
{
    use HasAdvancedFilter;

    /** @var string[] */
    public $orderable = [
        'id',
        'purchase_id',
        'payment_method',
        'amount',
        'payment_date',
        'created_at',
        'updated_at',
    ];

    /** @var string[] */
    public $filterable = [
        'id',
        'purchase_id',
        'payment_method',
        'amount',
        'payment_date',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
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
    public function scopeByPurchase($query)
    {
        return $query->wherePurchaseId(request()->route('purchase_id'));
    }
}
