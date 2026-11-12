<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExpeditionRegistrationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'consented_at' => now(),
        ];
    }
}
