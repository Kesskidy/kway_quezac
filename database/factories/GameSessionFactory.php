<?php

namespace Database\Factories;

use App\Models\GameSession;
use App\Models\PromoCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameSession>
 */
class GameSessionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'promo_code_id' => PromoCode::factory()->used(),
            'won' => false,
            'scenario' => 0,
        ];
    }

    public function won(int $scenario = 0): static
    {
        return $this->state(fn () => ['won' => true, 'scenario' => $scenario]);
    }

    public function lost(int $scenario = 0): static
    {
        return $this->state(fn () => ['won' => false, 'scenario' => $scenario]);
    }
}
