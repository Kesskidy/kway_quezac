<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'first_name' => fake()->firstName(),
            'email' => fake()->safeEmail(),
            'size' => 'M',
        ];
    }
}
