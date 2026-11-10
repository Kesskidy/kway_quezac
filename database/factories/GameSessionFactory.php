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
            'course' => [
                'phases' => [['type' => 'rain', 'start_tick' => 0, 'end_tick' => 100]],
                'trees' => [['start' => 50, 'end' => 80]],
            ],
        ];
    }

    public function started(): static
    {
        return $this->state(fn () => ['started_at' => now()]);
    }

    public function finished(): static
    {
        return $this->state(fn () => ['started_at' => now()->subMinute(), 'finished_at' => now()]);
    }
}
