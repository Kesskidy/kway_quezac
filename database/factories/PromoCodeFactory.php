<?php

namespace Database\Factories;

use App\Models\PromoCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PromoCode>
 */
class PromoCodeFactory extends Factory
{
    /**
     * Same format as the example in regle.md: QZK-XXXX-XXXX.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'QZK-'.Str::upper(Str::random(4)).'-'.Str::upper(Str::random(4)),
            'used_at' => null,
        ];
    }

    public function used(): static
    {
        return $this->state(fn () => ['used_at' => now()]);
    }
}
