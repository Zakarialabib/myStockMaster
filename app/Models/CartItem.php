<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'associable_type',
        'associable_id',
        'name',
        'price',
        'quantity',
        'attributes',
        'conditions',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price'      => 'decimal:2',
            'quantity'   => 'integer',
            'attributes' => 'array',
            'conditions' => 'array',
        ];
    }

    /** Get the cart that owns this item */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /** Get the associated model (Product, Service, etc.) */
    public function associable(): MorphTo
    {
        return $this->morphTo();
    }

    /** Get the item's subtotal */
    protected function subTotal(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->price * $this->quantity,
        );
    }

    /** Get the item's price with conditions applied */
    protected function priceWithConditions(): Attribute
    {
        return Attribute::make(
            get: function () {
                $price = $this->price;

                if (! empty($this->conditions)) {
                    foreach ($this->conditions as $condition) {
                        $price = $this->applyCondition($price, $condition);
                    }
                }

                return max(0, $price);
            }
        );
    }

    /** Get the item's subtotal with conditions applied */
    protected function subTotalWithConditions(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->price_with_conditions * $this->quantity,
        );
    }

    /** Apply a condition to a price */
    protected function applyCondition(float $amount, array $condition): float
    {
        $value = $condition['value'] ?? 0;
        $type = $condition['type'] ?? 'fixed';

        if (is_string($value)) {
            // Handle percentage values (e.g., "10%", "-5%")
            if (str_ends_with($value, '%')) {
                $percentage = (float) str_replace('%', '', $value);

                return $amount + ($amount * ($percentage / 100));
            }

            // Handle fixed values with + or - prefix
            if (str_starts_with($value, '+') || str_starts_with($value, '-')) {
                return $amount + (float) $value;
            }

            // Default to fixed value
            return $amount + (float) $value;
        }

        // Handle numeric values based on type
        switch ($type) {
            case 'percentage':
                return $amount + ($amount * ($value / 100));
            case 'fixed':
            default:
                return $amount + $value;
        }
    }

    /** Add a condition to this item */
    public function addCondition(array $condition): self
    {
        $conditions = $this->conditions ?? [];
        $conditions[] = array_merge([
            'name'  => 'Condition',
            'type'  => 'fixed',
            'value' => 0,
            'order' => 0,
        ], $condition);

        $this->conditions = $conditions;

        return $this;
    }

    /** Remove a condition by name */
    public function removeCondition(string $name): self
    {
        $conditions = $this->conditions ?? [];
        $this->conditions = array_filter($conditions, function ($condition) use ($name) {
            return ($condition['name'] ?? '') !== $name;
        });

        return $this;
    }

    /** Get formatted price */
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => '$'.number_format((float) $this->price, 2),
        );
    }

    /** Get formatted subtotal */
    protected function formattedSubTotal(): Attribute
    {
        return Attribute::make(
            get: fn () => '$'.number_format((float) $this->sub_total, 2),
        );
    }

    /** Get formatted price with conditions */
    protected function formattedPriceWithConditions(): Attribute
    {
        return Attribute::make(
            get: fn () => '$'.number_format($this->price_with_conditions, 2),
        );
    }

    /** Get formatted subtotal with conditions */
    protected function formattedSubTotalWithConditions(): Attribute
    {
        return Attribute::make(
            get: fn () => '$'.number_format($this->sub_total_with_conditions, 2),
        );
    }

    /** Scope to filter by cart */
    public function scopeForCart($query, string $cartId)
    {
        return $query->where('cart_id', $cartId);
    }

    /** Scope to filter by associable model */
    public function scopeForModel($query, string $type, int $id)
    {
        return $query->where('associable_type', $type)
            ->where('associable_id', $id);
    }
}
