<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CashRegister extends Model
{
    use HasAdvancedFilter;

    /** @var array<int, string> */
    public const ATTRIBUTES = [
        'id', 'cash_in_hand', 'user_id', 'warehouse_id', 'status', 'recieved', 'sent',
    ];

    public $orderable = self::ATTRIBUTES;
    public $filterable = self::ATTRIBUTES;

    /** @var array<int, string> */
    protected $fillable = [
        'cash_in_hand', 'user_id', 'warehouse_id', 'status', 'recieved', 'sent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the recieved amount.
     *
     * @return Attribute
     */
    protected function recieved(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => $value,
        );
    }

    /**
     * Get the sent amount.
     *
     * @return Attribute
     */
    protected function sent(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => $value,
        );
    }

    /**
     * Scope a query to only include cash registers with a specific status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include cash registers with a specific user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
