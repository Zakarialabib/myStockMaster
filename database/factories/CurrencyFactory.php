<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'               => $this->faker->name(),
            'code'               => $this->faker->currencyCode(),
            'symbol'             => $this->faker->currencySymbol(),
            'thousand_separator' => $this->faker->randomElement([',', '']),
            'decimal_separator'  => $this->faker->randomElement(['.', '']),
            'exchange_rate'      => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
