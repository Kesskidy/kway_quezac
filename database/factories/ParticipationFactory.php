<?php

namespace Database\Factories;

use App\Models\GameSession;
use App\Models\Participation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Participation>
 */
class ParticipationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'game_session_id' => GameSession::factory()->won(),
            'first_name' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'consented_at' => now(),
        ];
    }
}
