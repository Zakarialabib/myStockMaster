<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                             $id
 * @property string                          $instance_name
 * @property string|null                     $user_id
 * @property string|null                     $session_id
 * @property array<array-key, mixed>|null    $conditions
 * @property numeric                         $tax_rate
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $discount
 * @property-read mixed $formatted_discount
 * @property-read mixed $formatted_sub_total
 * @property-read mixed $formatted_tax
 * @property-read mixed $formatted_total
 * @property-read mixed $item_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CartItem> $items
 * @property-read int|null $items_count
 * @property-read mixed $quantity_count
 * @property-read mixed $sub_total
 * @property-read mixed $sub_total_with_conditions
 * @property-read mixed $tax
 * @property-read mixed $total
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart forInstance(string $instanceName)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart forSession(string $sessionId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart forUser(int $userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereConditions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereInstanceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'instance_name',
        'user_id',
        'session_id',
        'conditions',
        'tax_rate',
        'expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'conditions' => 'array',
            'tax_rate' => 'decimal:2',
            'expires_at' => 'datetime',
        ];
    }

    /** Get the user that owns this cart */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Get all items in this cart */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /** Get the cart's subtotal */
    protected function subTotal(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->items->sum('sub_total'),
        );
    }

    /** Get the cart's subtotal with conditions */
    protected function subTotalWithConditions(): Attribute
    {
        return Attribute::make(
            get: function () {
                $subtotal = $this->items->sum('sub_total');

                return $this->applyConditionsToAmount($subtotal, 'subtotal');
            }
        );
    }

    /** Get the cart's tax amount */
    protected function tax(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->tax_rate <= 0) {
                    return 0.0;
                }

                $taxableAmount = $this->items->sum(function ($item) {
                    // Check if item is tax exempt
                    $attributes = $item->attributes ?? [];

                    if (isset($attributes['tax_exempt']) && $attributes['tax_exempt']) {
                        return 0;
                    }

                    return $item->sub_total_with_conditions;
                });

                $tax = $taxableAmount * ($this->tax_rate / 100);

                return $this->applyConditionsToAmount($tax, 'tax');
            }
        );
    }

    /** Get the cart's total */
    protected function total(): Attribute
    {
        return Attribute::make(
            get: function () {
                $total = $this->sub_total_with_conditions + $this->tax;

                return $this->applyConditionsToAmount($total, 'total');
            }
        );
    }

    /** Get the cart's item count */
    protected function itemCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->items->count(),
        );
    }

    /** Get the cart's total quantity */
    protected function quantityCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->items->sum('quantity'),
        );
    }

    /** Get discount amount */
    protected function discount(): Attribute
    {
        return Attribute::make(
            get: function () {
                $discountConditions = $this->getConditionsByType('discount');
                $subtotal = $this->sub_total;

                $totalDiscount = 0;

                foreach ($discountConditions as $condition) {
                    $discount = $this->applyCondition($subtotal, $condition) - $subtotal;
                    $totalDiscount += abs($discount); // Make positive for display
                }

                return $totalDiscount;
            }
        );
    }

    /** Apply conditions to an amount */
    protected function applyConditionsToAmount(float $amount, string $target): float
    {
        $conditions = $this->conditions ?? [];
        $applicableConditions = array_filter($conditions, function ($condition) use ($target) {
            return ($condition['target'] ?? 'subtotal') === $target;
        });

        // Sort conditions by order
        usort($applicableConditions, function ($a, $b) {
            return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
        });

        foreach ($applicableConditions as $condition) {
            $amount = $this->applyCondition($amount, $condition);
        }

        return max(0, $amount); // Ensure amount is not negative
    }

    /** Apply a single condition to an amount */
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

    /** Add a condition to the cart */
    public function addCondition(array $condition): self
    {
        $conditions = $this->conditions ?? [];
        $conditions[] = array_merge([
            'name' => 'Condition',
            'type' => 'fixed',
            'target' => 'subtotal',
            'value' => 0,
            'order' => 0,
            'attributes' => [],
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

    /** Get all conditions */
    public function getConditions(): array
    {
        return $this->conditions ?? [];
    }

    /** Get conditions by type */
    public function getConditionsByType(string $type): array
    {
        $conditions = $this->conditions ?? [];

        return array_filter($conditions, function ($condition) use ($type) {
            return ($condition['type'] ?? '') === $type;
        });
    }

    /** Clear all conditions */
    public function clearConditions(): self
    {
        $this->conditions = [];

        return $this;
    }

    /** Get cart summary */
    public function getSummary(): array
    {
        return [
            'subtotal' => $this->sub_total_with_conditions,
            'tax' => $this->tax,
            'discount' => $this->discount,
            'total' => $this->total,
            'item_count' => $this->item_count,
            'quantity_count' => $this->quantity_count,
        ];
    }

    /** Get formatted subtotal */
    protected function formattedSubTotal(): Attribute
    {
        return Attribute::make(
            get: fn () => '$' . number_format($this->sub_total_with_conditions, 2),
        );
    }

    /** Get formatted tax */
    protected function formattedTax(): Attribute
    {
        return Attribute::make(
            get: fn () => '$' . number_format($this->tax, 2),
        );
    }

    /** Get formatted total */
    protected function formattedTotal(): Attribute
    {
        return Attribute::make(
            get: fn () => '$' . number_format($this->total, 2),
        );
    }

    /** Get formatted discount */
    protected function formattedDiscount(): Attribute
    {
        return Attribute::make(
            get: fn () => '$' . number_format($this->discount, 2),
        );
    }

    /** Check if cart is empty */
    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }

    /** Check if cart has expired */
    public function hasExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /** Scope to filter by instance name */
    public function scopeForInstance($query, string $instanceName)
    {
        return $query->where('instance_name', $instanceName);
    }

    /** Scope to filter by user */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /** Scope to filter by session */
    public function scopeForSession($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /** Scope to filter non-expired carts */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }
}
