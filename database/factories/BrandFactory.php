<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'        => fake()->company(),
            'description' => fake()->realText(100),
            'image'       => 'https://www.apple.com/v/iphone/home/ah/images/overview/compare/compare_iphone_12__f2x.png',
        ];
    }
}
