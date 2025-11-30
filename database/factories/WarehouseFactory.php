<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warehouse>
 */
class WarehouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Warehouse::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id'      => $this->faker->unique()->numberBetween(1, 1000),
            'name'    => $this->faker->company.' Warehouse',
            'city'    => $this->faker->city,
            'phone'   => $this->faker->phoneNumber,
            'email'   => $this->faker->unique()->safeEmail,
            'country' => $this->faker->country,
        ];
    }

    /** Indicate that the warehouse is the main warehouse. */
    public function main(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Main Warehouse',
        ]);
    }
}
