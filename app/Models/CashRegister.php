<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Support\HasAdvancedFilter;

/**
 * App\Models\CashRegister
 *
 * @property int $id
 * @property float $cash_in_hand
 * @property int|null $user_id
 * @property int|null $warehouse_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\Warehouse|null $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereCashInHand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereWarehouseId($value)
 *
 * @mixin \Eloquent
 */
class CashRegister extends Model
{
    
    use HasAdvancedFilter;
    protected $fillable = ['cash_in_hand', 'user_id', 'warehouse_id', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
