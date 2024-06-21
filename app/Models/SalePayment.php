<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class SalePayment extends Model
{
    use HasAdvancedFilter;

    public const ATTRIBUTES = [
        'id',
        'sale_id',
        'payment_id',
        'reference',
        'amount',
        'created_at',
        'updated_at',

    ];

    public $orderable = self::ATTRIBUTES;
    public $filterable = self::ATTRIBUTES;
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
        'payment_id',
        'user_id',
        'cash_register_id',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($salePayment) {
            $prefix = settings()->salePayment_prefix;

            $latestSalePayment = self::latest()->first();

            if ($latestSalePayment) {
                $number = intval(substr($latestSalePayment->reference, -3)) + 1;
            } else {
                $number = 1;
            }

            $salePayment->reference = $prefix.str_pad(strval($number), 3, '0', STR_PAD_LEFT);
        });
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    public function scopeBySale($query)
    {
        return $query->whereSaleId(request()->route('sale_id'));
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
