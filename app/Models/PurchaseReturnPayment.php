<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * App\Models\PurchaseReturnPayment
 *
 * @property int $id
 * @property int $purchase_return_id
 * @property int $amount
 * @property string $date
 * @property string $reference
 * @property string $payment_method
 * @property string|null $note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\PurchaseReturn $purchaseReturn
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnPayment advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnPayment byPurchaseReturn()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnPayment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnPayment whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnPayment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnPayment wherePurchaseReturnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnPayment whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReturnPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PurchaseReturnPayment extends Model
{
    use HasAdvancedFilter;

    /** @var string[] */
    public $orderable = [
        'id',
        'date',
        'reference',
        'amount',
        'note',
        'purchase_return_id',
        'payment_method',
        'created_at',
        'updated_at',
    ];

    /** @var string[] */
    public $filterable = [
        'id',
        'date',
        'reference',
        'amount',
        'note',
        'purchase_return_id',
        'payment_method',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    public function purchaseReturn(): BelongsTo
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id', 'id');
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
    public function scopeByPurchaseReturn($query)
    {
        return $query->wherePurchaseReturnId(request()->route('purchase_return_id'));
    }
}
