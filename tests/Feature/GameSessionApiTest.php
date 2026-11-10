<?php

namespace Tests\Feature;

use App\Models\GameSession;
use App\Models\PromoCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class GameSessionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_valid_code_starts_a_game_and_is_consumed(): void
    {
        $promoCode = PromoCode::factory()->create(['code' => 'QZK-7F3A-92XC']);

        $response = $this->postJson('/api/game-sessions', ['code' => 'QZK-7F3A-92XC']);

        $response->assertCreated()->assertExactJsonStructure(['data' => ['token']]);

        $gameSession = GameSession::firstOrFail();
        $this->assertSame($response->json('data.token'), $gameSession->token);
        $this->assertSame(26, strlen($gameSession->token)); // ULID
        $this->assertTrue($gameSession->promoCode->is($promoCode));
        $this->assertNotNull($promoCode->fresh()->used_at);
        $this->assertNull($gameSession->started_at);
        $this->assertNotEmpty($gameSession->course['phases']);
    }

    public function test_the_code_is_normalized(): void
    {
        PromoCode::factory()->create(['code' => 'QZK-7F3A-92XC']);

        $this->postJson('/api/game-sessions', ['code' => '  qzk-7f3a-92xc '])->assertCreated();
    }

    public function test_a_code_can_only_be_used_once(): void
    {
        PromoCode::factory()->create(['code' => 'QZK-7F3A-92XC']);

        $this->postJson('/api/game-sessions', ['code' => 'QZK-7F3A-92XC'])->assertCreated();

        $this->postJson('/api/game-sessions', ['code' => 'QZK-7F3A-92XC'])
            ->assertStatus(409)
            ->assertExactJson([
                'message' => 'Ce code a déjà été utilisé.',
                'errors' => ['code' => ['Ce code a déjà été utilisé.']],
            ]);

        $this->assertSame(1, GameSession::count());
    }

    public function test_an_unknown_code_is_rejected(): void
    {
        $this->postJson('/api/game-sessions', ['code' => 'NOPE'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['code' => 'Ce code est invalide.']);

        $this->assertSame(0, GameSession::count());
    }

    public function test_a_missing_code_is_rejected(): void
    {
        $this->postJson('/api/game-sessions', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['code' => 'Saisis ton code pour jouer.']);
    }

    public function test_attempts_are_throttled(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/api/game-sessions', ['code' => 'NOPE'])->assertUnprocessable();
        }

        $this->postJson('/api/game-sessions', ['code' => 'NOPE'])
            ->assertTooManyRequests()
            ->assertJson(['message' => 'Trop d’essais. Réessaie dans une minute.'])
            ->assertHeader('Retry-After');
    }

    public function test_course_returns_phases_trees_and_rules(): void
    {
        $gameSession = GameSession::factory()->create();

        $this->getJson("/api/game-sessions/{$gameSession->token}/course")
            ->assertOk()
            ->assertExactJsonStructure([
                'data' => [
                    'phases' => [['type', 'start_tick', 'end_tick']],
                    'trees' => [['start', 'end']],
                    'rules' => [
                        'ticks_per_second',
                        'course_length',
                        'speed',
                        'speed_hand_wet',
                        'bottle_capacity',
                        'rain_per_tick',
                        'max_fill_percent_for_wheel',
                        'max_ticks',
                    ],
                ],
            ])
            ->assertJsonPath('data.rules.course_length', config('game.course_length'));
    }

    public function test_started_at_is_recorded_on_the_first_call_only(): void
    {
        $gameSession = GameSession::factory()->create();

        Carbon::setTestNow('2026-11-10 14:00:00');
        $this->getJson("/api/game-sessions/{$gameSession->token}/course")->assertOk();

        Carbon::setTestNow('2026-11-10 14:00:30');
        $this->getJson("/api/game-sessions/{$gameSession->token}/course")->assertOk();

        $this->assertSame('2026-11-10 14:00:00', $gameSession->fresh()->started_at->toDateTimeString());
    }

    public function test_course_of_an_unknown_game_returns_404(): void
    {
        $this->getJson('/api/game-sessions/01JC8Z3K4V8Q6R2M5T7W9Y1A3B/course')
            ->assertNotFound()
            ->assertExactJson(['message' => 'Partie introuvable.']);
    }

    public function test_course_of_a_finished_game_returns_409(): void
    {
        $gameSession = GameSession::factory()->finished()->create();

        $this->getJson("/api/game-sessions/{$gameSession->token}/course")
            ->assertStatus(409)
            ->assertJson(['message' => 'Cette partie est déjà terminée.']);
    }
}
