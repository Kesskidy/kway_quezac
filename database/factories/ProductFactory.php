<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Veste RE·SOURCE',
            'slug' => fake()->unique()->slug(),
            'description' => 'Veste de pluie.',
            'price_cents' => 30000,
        ];
    }
}
