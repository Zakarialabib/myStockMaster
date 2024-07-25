<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class SaleReturnPayment extends Model
{
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

    /** @return BelongsTo */
    public function saleReturn(): BelongsTo
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id', 'id');
    }

    /**
     * Get ajustement date.
     *
     * @return Attribute
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
     *
     * @return Attribute
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
}
