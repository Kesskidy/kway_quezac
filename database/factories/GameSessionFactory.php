<?php

namespace Database\Factories;

use App\Models\PromoCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameSessionFactory extends Factory
{
    public function definition(): array
    {
        return ['promo_code_id' => PromoCode::factory()->used(), 'won' => true, 'scenario' => 0];
    }

    public function lost(): static
    {
        return $this->state(['won' => false]);
    }
}
