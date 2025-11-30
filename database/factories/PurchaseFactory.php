<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Purchase::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 200, 2000);
        $taxPercentage = $this->faker->numberBetween(0, 25);
        $discountPercentage = $this->faker->numberBetween(0, 10);

        $taxAmount = ($subtotal * $taxPercentage) / 100;
        $discountAmount = ($subtotal * $discountPercentage) / 100;
        $shippingAmount = $this->faker->randomFloat(2, 0, 100);

        $totalAmount = $subtotal + $taxAmount - $discountAmount + $shippingAmount;
        $paidAmount = $this->faker->randomFloat(2, 0, $totalAmount);
        $dueAmount = $totalAmount - $paidAmount;

        return [
            'id'                  => Str::uuid(),
            'date'                => $this->faker->date(),
            'reference'           => 'PU-'.str_pad($this->faker->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'supplier_id'         => Supplier::factory(),
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
            'status'              => $this->faker->randomElement(['pending', 'ordered', 'received']),
            'payment_status'      => $this->faker->randomElement(['pending', 'paid', 'partial']),
            'document'            => null,
            'note'                => $this->faker->optional()->sentence(),
        ];
    }

    /** Indicate that the purchase is received. */
    public function received(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'received',
        ]);
    }

    /** Indicate that the purchase is fully paid. */
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

    /** Indicate that the purchase is pending payment. */
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
