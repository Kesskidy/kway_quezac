<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PromoCodeFactory extends Factory
{
    public function definition(): array
    {
        return ['code' => 'QZK-'.Str::upper(Str::random(4)).'-'.Str::upper(Str::random(4))];
    }

    public function used(): static
    {
        return $this->state(['used_at' => now()]);
    }
}
