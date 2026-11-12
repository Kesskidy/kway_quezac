<?php

namespace Database\Factories;

use App\Models\GameSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParticipationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'game_session_id' => GameSession::factory(),
            'first_name' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'consented_at' => now(),
        ];
    }
}
