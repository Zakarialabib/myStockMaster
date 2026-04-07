<?php

declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int                             $id
 * @property int                             $cart_id
 * @property string|null                     $associable_type
 * @property int|null                        $associable_id
 * @property string                          $name
 * @property numeric                         $price
 * @property int                             $quantity
 * @property array<array-key, mixed>|null    $attributes
 * @property array<array-key, mixed>|null    $conditions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Illuminate\Database\Eloquent\Model|null $associable
 * @property-read Cart $cart
 * @property-read mixed $formatted_price
 * @property-read mixed $formatted_price_with_conditions
 * @property-read mixed $formatted_sub_total
 * @property-read mixed $formatted_sub_total_with_conditions
 * @property-read mixed $price_with_conditions
 * @property-read mixed $sub_total
 * @property-read mixed $sub_total_with_conditions
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem forCart(string $cartId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem forModel(string $type, int $id)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereAssociableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereAssociableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereConditions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
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
    #[\Override]
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
            'attributes' => 'array',
            'conditions' => 'array',
        ];
    }

    /** Get the cart that owns this item
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Cart, $this> */
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
            get: fn (): int|float => $this->price * $this->quantity,
        );
    }

    /** Get the item's price with conditions applied */
    protected function priceWithConditions(): Attribute
    {
        return Attribute::make(
            get: function () {
                $price = $this->price;

                if (filled($this->conditions)) {
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
            get: fn (): int|float => $this->price_with_conditions * $this->quantity,
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

            // Default to fixed value
            return $amount + (float) $value;
        }

        // Handle numeric values based on type
        return match ($type) {
            'percentage' => $amount + ($amount * ($value / 100)),
            default => $amount + $value,
        };
    }

    /** Add a condition to this item */
    public function addCondition(array $condition): self
    {
        $conditions = $this->conditions ?? [];
        $conditions[] = array_merge([
            'name' => 'Condition',
            'type' => 'fixed',
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
        $this->conditions = array_filter($conditions, fn(array $condition) => ($condition['name'] ?? '') !== $name);

        return $this;
    }

    /** Get formatted price */
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn (): string => '$' . number_format((float) $this->price, 2),
        );
    }

    /** Get formatted subtotal */
    protected function formattedSubTotal(): Attribute
    {
        return Attribute::make(
            get: fn (): string => '$' . number_format((float) $this->sub_total, 2),
        );
    }

    /** Get formatted price with conditions */
    protected function formattedPriceWithConditions(): Attribute
    {
        return Attribute::make(
            get: fn (): string => '$' . number_format($this->price_with_conditions, 2),
        );
    }

    /** Get formatted subtotal with conditions */
    protected function formattedSubTotalWithConditions(): Attribute
    {
        return Attribute::make(
            get: fn (): string => '$' . number_format($this->sub_total_with_conditions, 2),
        );
    }

    /** Scope to filter by cart */
    protected function scopeForCart(mixed $query, string $cartId)
    {
        return $query->where('cart_id', $cartId);
    }

    /** Scope to filter by associable model */
    protected function scopeForModel(mixed $query, string $type, int $id)
    {
        return $query->where('associable_type', $type)
            ->where('associable_id', $id);
    }
}
