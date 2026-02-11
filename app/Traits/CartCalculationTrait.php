<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Collection;

trait CartCalculationTrait
{
    /** Tax rate (percentage) */
    protected float $taxRate = 0.0;

    /** Global cart conditions */
    protected array $conditions = [];

    /** Calculate subtotal for cart items */
    protected function calculateSubTotal(Collection $items): float
    {
        $subtotal = 0;

        foreach ($items as $item) {
            if (is_array($item)) {
                $subtotal += $this->calculateItemSubTotal($item);
            }
        }

        return $this->applyConditionsToAmount($subtotal, 'subtotal');
    }

    /** Calculate item subtotal */
    protected function calculateItemSubTotal(array $item): float
    {
        $subtotal = $item['price'] * $item['quantity'];

        // Apply item-specific conditions
        if ( ! empty($item['conditions'])) {
            $subtotal = $this->applyItemConditions($subtotal, $item['conditions']);
        }

        return $subtotal;
    }

    /** Calculate total with tax */
    protected function calculateTotal(Collection $items): float
    {
        $subtotal = $this->calculateSubTotal($items);
        $tax = $this->calculateTax($items);
        $total = $subtotal + $tax;

        return $this->applyConditionsToAmount($total, 'total');
    }

    /** Calculate tax amount */
    protected function calculateTax(Collection $items): float
    {
        if ($this->taxRate <= 0) {
            return 0.0;
        }

        $taxableAmount = $items->sum(function ($item) {
            // Ignore non-item values (e.g., global settings stored as scalars)
            if ( ! is_array($item)) {
                return 0;
            }

            // Check if item is taxable
            $attributes = $item['attributes'] ?? [];

            if (isset($attributes['tax_exempt']) && $attributes['tax_exempt']) {
                return 0;
            }

            return $this->calculateItemSubTotal($item);
        });

        $tax = $taxableAmount * ($this->taxRate / 100);

        return $this->applyConditionsToAmount($tax, 'tax');
    }

    /** Apply conditions to an amount */
    protected function applyConditionsToAmount(float $amount, string $target): float
    {
        $applicableConditions = array_filter($this->conditions, function ($condition) use ($target) {
            return is_array($condition) && ($condition['target'] ?? 'subtotal') === $target;
        });

        // Sort conditions by order
        usort($applicableConditions, function ($a, $b) {
            return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
        });

        foreach ($applicableConditions as $condition) {
            // Ensure condition is an array
            if ( ! is_array($condition)) {
                continue;
            }

            $amount = $this->applyCondition($amount, $condition);
        }

        return max(0, $amount); // Ensure amount is not negative
    }

    /** Apply item-specific conditions */
    protected function applyItemConditions(float $amount, array $conditions): float
    {
        // Filter out non-array conditions
        $validConditions = array_filter($conditions, 'is_array');

        // Sort conditions by order
        usort($validConditions, function ($a, $b) {
            return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
        });

        foreach ($validConditions as $condition) {
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
        $this->conditions[] = array_merge([
            'name'       => 'Condition',
            'type'       => 'fixed',
            'target'     => 'subtotal',
            'value'      => 0,
            'order'      => 0,
            'attributes' => [],
        ], $condition);

        return $this;
    }

    /** Remove a condition by name */
    public function removeCondition(string $name): self
    {
        $this->conditions = array_filter($this->conditions, function ($condition) use ($name) {
            return ($condition['name'] ?? '') !== $name;
        });

        return $this;
    }

    /** Get all conditions */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /** Get conditions by type */
    public function getConditionsByType(string $type): array
    {
        return array_filter($this->conditions, function ($condition) use ($type) {
            return is_array($condition) && ($condition['type'] ?? '') === $type;
        });
    }

    /** Clear all conditions */
    public function clearConditions(): self
    {
        $this->conditions = [];

        return $this;
    }

    /** Set tax rate */
    public function setTaxRate(float $rate): self
    {
        $this->taxRate = max(0, $rate);

        return $this;
    }

    /** Get tax rate */
    public function getTaxRate(): float
    {
        return $this->taxRate;
    }

    /** Calculate item price with conditions */
    public function getItemPriceWithConditions(array $item): float
    {
        $price = $item['price'];

        if ( ! empty($item['conditions'])) {
            $price = $this->applyItemConditions($price, $item['conditions']);
        }

        return max(0, $price);
    }

    /** Calculate item subtotal with conditions */
    public function getItemSubTotalWithConditions(array $item): float
    {
        return $this->calculateItemSubTotal($item);
    }

    /** Get formatted price */
    public function formatPrice(float $price, string $currency = '$', int $decimals = 2): string
    {
        return $currency.number_format($price, $decimals);
    }

    /** Calculate discount amount */
    public function calculateDiscount(Collection $items): float
    {
        return $this->getDiscountAmount($items);
    }

    /** Calculate discount amount */
    public function getDiscountAmount(Collection $items): float
    {
        $discountConditions = $this->getConditionsByType('discount');
        $subtotal = $items->sum(function ($item) {
            // Ignore non-item values (e.g., global settings stored as scalars)
            if ( ! is_array($item)) {
                return 0;
            }
            $price = isset($item['price']) ? (float) $item['price'] : 0.0;
            $qty = isset($item['quantity']) ? (int) $item['quantity'] : 0;

            return $price * $qty;
        });

        $totalDiscount = 0;

        foreach ($discountConditions as $condition) {
            // Ensure condition is an array
            if ( ! is_array($condition)) {
                continue;
            }

            $discount = $this->applyCondition($subtotal, $condition) - $subtotal;
            $totalDiscount += abs($discount); // Make positive for display
        }

        return $totalDiscount;
    }

    /** Get cart summary */
    public function getCartSummary(Collection $items): array
    {
        $subtotal = $this->calculateSubTotal($items);
        $tax = $this->calculateTax($items);
        $discount = $this->getDiscountAmount($items);
        $total = $this->calculateTotal($items);

        return [
            'subtotal'       => $subtotal,
            'tax'            => $tax,
            'discount'       => $discount,
            'total'          => $total,
            'item_count'     => $items->count(),
            'quantity_count' => $items->sum('quantity'),
        ];
    }
}
