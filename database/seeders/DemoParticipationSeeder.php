<?php

namespace Database\Seeders;

use App\Models\GameSession;
use App\Models\Participation;
use App\Models\PromoCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Demo participations, so the admin page and `php artisan lottery:draw` can be shown
 * without first winning games by hand. Fictitious people with example.com addresses.
 * Called by DatabaseSeeder in the local environment only.
 */
class DemoParticipationSeeder extends Seeder
{
    /**
     * Camille appears twice: two winning codes, but only one prize possible.
     */
    private const PEOPLE = [
        ['Camille', 'camille@example.com'],
        ['Camille', 'camille@example.com'],
        ['Alex', 'alex@example.com'],
        ['Sam', 'sam@example.com'],
        ['Inès', 'ines@example.com'],
        ['Hugo', 'hugo@example.com'],
        ['Léa', 'lea@example.com'],
        ['Noé', 'noe@example.com'],
    ];

    public function run(): void
    {
        DB::transaction(function () {
            foreach (self::PEOPLE as $index => [$firstName, $email]) {
                $promoCode = PromoCode::create([
                    'code' => sprintf('DEMO-%s-%02d', Str::upper(Str::random(4)), $index + 1),
                    'used_at' => now()->subDays(10 - $index),
                ]);

                $gameSession = GameSession::create([
                    'promo_code_id' => $promoCode->id,
                    'won' => true,
                    'scenario' => $index % config('game.scenarios.won'),
                ]);

                Participation::create([
                    'game_session_id' => $gameSession->id,
                    'first_name' => $firstName,
                    'email' => $email,
                    'consented_at' => $promoCode->used_at,
                ]);
            }
        });

        $this->command?->info(count(self::PEOPLE).' participations de démonstration créées (déjà utilisées, gagnantes).');
    }
}
