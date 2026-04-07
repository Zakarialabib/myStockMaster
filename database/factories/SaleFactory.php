<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(nbMaxDecimals: 2, min: 100, max: 1000);
        $taxPercentage = fake()->numberBetween(int1: 0, int2: 20);
        $discountPercentage = fake()->numberBetween(int1: 0, int2: 15);

        $taxAmount = ($subtotal * $taxPercentage) / 100;
        $discountAmount = ($subtotal * $discountPercentage) / 100;
        $shippingAmount = fake()->randomFloat(nbMaxDecimals: 2, min: 0, max: 50);

        $totalAmount = $subtotal + $taxAmount - $discountAmount + $shippingAmount;
        $paidAmount = fake()->randomFloat(nbMaxDecimals: 2, min: 0, max: $totalAmount);
        $dueAmount = $totalAmount - $paidAmount;

        return [
            'id' => Str::uuid(),
            'date' => fake()->date(),
            'reference' => 'SL-' . str_pad((string) fake()->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'customer_id' => Customer::factory(),
            'user_id' => User::factory(),
            'warehouse_id' => Warehouse::factory(),
            'tax_percentage' => $taxPercentage,
            'tax_amount' => $taxAmount,
            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'shipping_amount' => $shippingAmount,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
            'status' => fake()->randomElement(['pending', 'ordered', 'completed']),
            'payment_status' => fake()->randomElement(['pending', 'paid', 'partial']),
            'shipping_status' => fake()->randomElement(['pending', 'shipped', 'delivered']),
            'document' => null,
            'note' => fake()->optional()->sentence(),
        ];
    }

    /** Indicate that the sale is completed. */
    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'completed',
        ]);
    }

    /** Indicate that the sale is fully paid. */
    public function paid(): static
    {
        return $this->state(fn(array $attributes) => [
            'payment_status' => 'paid',
            'paid_amount' => $attributes['total_amount'],
            'due_amount' => 0,
        ]);
    }

    /** Indicate that the sale is pending payment. */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'payment_status' => 'pending',
            'paid_amount' => 0,
            'due_amount' => $attributes['total_amount'],
        ]);
    }
}
