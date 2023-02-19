<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Shipment
 *
 * @property-read \App\Models\Sale $sale
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment query()
 *
 * @mixin \Eloquent
 */
class Shipment extends Model
{
    public $orderable = [
        'user_id', 'date', 'Ref', 'sale_id', 'delivered_to', 'shipping_address', 'status', 'shipping_details',
    ];
    public $filterable = [
        'user_id', 'date', 'Ref', 'sale_id', 'delivered_to', 'shipping_address', 'status', 'shipping_details',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 'date', 'Ref', 'sale_id', 'delivered_to', 'shipping_address', 'status', 'shipping_details',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'sale_id' => 'integer',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
