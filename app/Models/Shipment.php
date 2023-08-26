<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Support\HasAdvancedFilter;

class Shipment extends Model
{
    use HasAdvancedFilter;
    public const ATTRIBUTES = [
        'user_id', 'date', 'Ref', 'sale_id', 'delivered_to', 'status',

    ];

    public $orderable = self::ATTRIBUTES;
    public $filterable = self::ATTRIBUTES;

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
