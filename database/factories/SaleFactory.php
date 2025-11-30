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
        $subtotal = $this->faker->randomFloat(2, 100, 1000);
        $taxPercentage = $this->faker->numberBetween(0, 20);
        $discountPercentage = $this->faker->numberBetween(0, 15);

        $taxAmount = ($subtotal * $taxPercentage) / 100;
        $discountAmount = ($subtotal * $discountPercentage) / 100;
        $shippingAmount = $this->faker->randomFloat(2, 0, 50);

        $totalAmount = $subtotal + $taxAmount - $discountAmount + $shippingAmount;
        $paidAmount = $this->faker->randomFloat(2, 0, $totalAmount);
        $dueAmount = $totalAmount - $paidAmount;

        return [
            'id'                  => Str::uuid(),
            'date'                => $this->faker->date(),
            'reference'           => 'SL-'.str_pad($this->faker->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'customer_id'         => Customer::factory(),
            'user_id'             => User::factory(),
            'warehouse_id'        => Warehouse::factory(),
            'tax_percentage'      => $taxPercentage,
            'tax_amount'          => $taxAmount,
            'discount_percentage' => $discountPercentage,
            'discount_amount'     => $discountAmount,
            'shipping_amount'     => $shippingAmount,
            'total_amount'        => $totalAmount,
            'paid_amount'         => $paidAmount,
            'due_amount'          => $dueAmount,
            'status'              => $this->faker->randomElement(['pending', 'ordered', 'completed']),
            'payment_status'      => $this->faker->randomElement(['pending', 'paid', 'partial']),
            'shipping_status'     => $this->faker->randomElement(['pending', 'shipped', 'delivered']),
            'document'            => null,
            'note'                => $this->faker->optional()->sentence(),
        ];
    }

    /** Indicate that the sale is completed. */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    /** Indicate that the sale is fully paid. */
    public function paid(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_status' => 'paid',
                'paid_amount'    => $attributes['total_amount'],
                'due_amount'     => 0,
            ];
        });
    }

    /** Indicate that the sale is pending payment. */
    public function pending(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_status' => 'pending',
                'paid_amount'    => 0,
                'due_amount'     => $attributes['total_amount'],
            ];
        });
    }
}
