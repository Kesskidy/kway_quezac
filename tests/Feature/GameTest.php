<?php

namespace Tests\Feature;

use App\Mail\LotteryWon;
use App\Mail\ParticipationConfirmed;
use App\Models\GameSession;
use App\Models\Participation;
use App\Models\PromoCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    private const PLAYER = ['first_name' => 'Camille', 'email' => 'camille@example.com', 'consent' => true];

    public function test_a_valid_code_is_consumed_and_returns_the_draw(): void
    {
        $promoCode = PromoCode::factory()->create(['code' => 'QZK-AAAA-0001']);

        $this->postJson('/api/game-sessions', ['code' => ' qzk-aaaa-0001 '])
            ->assertCreated()
            ->assertJsonStructure(['data' => ['token', 'won', 'scenario']]);

        $this->assertNotNull($promoCode->fresh()->used_at);
        $this->assertSame(1, GameSession::count());
    }

    public function test_a_code_used_twice_is_refused(): void
    {
        PromoCode::factory()->create(['code' => 'QZK-AAAA-0001']);
        $this->postJson('/api/game-sessions', ['code' => 'QZK-AAAA-0001'])->assertCreated();

        $this->postJson('/api/game-sessions', ['code' => 'QZK-AAAA-0001'])
            ->assertStatus(409)
            ->assertExactJson([
                'message' => 'Ce code a déjà été utilisé.',
                'errors' => ['code' => ['Ce code a déjà été utilisé.']],
            ]);
    }

    public function test_an_unknown_or_missing_code_is_refused(): void
    {
        $this->postJson('/api/game-sessions', ['code' => 'NOPE'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['code' => 'Ce code est invalide.']);

        $this->postJson('/api/game-sessions', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['code' => 'Saisis ton code pour jouer.']);
    }

    public function test_attempts_are_limited(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/api/game-sessions', ['code' => 'NOPE']);
        }

        $this->postJson('/api/game-sessions', ['code' => 'NOPE'])
            ->assertTooManyRequests()
            ->assertJson(['message' => 'Trop d’essais. Réessaie dans une minute.']);
    }

    public function test_a_winner_registers_a_participation(): void
    {
        Mail::fake();
        $gameSession = GameSession::factory()->create();

        $this->postJson("/api/game-sessions/{$gameSession->token}/participation", self::PLAYER)
            ->assertCreated()
            ->assertExactJson(['data' => ['message' => "C'est noté ! Ta participation au tirage des lots est enregistrée."]]);

        $this->assertSame('camille@example.com', $gameSession->participation->email);
        Mail::assertSent(ParticipationConfirmed::class);
    }

    public function test_a_lost_game_cannot_participate(): void
    {
        $gameSession = GameSession::factory()->lost()->create();

        $this->postJson("/api/game-sessions/{$gameSession->token}/participation", self::PLAYER)
            ->assertForbidden()
            ->assertExactJson(['message' => 'Cette partie n’est pas gagnante.']);
    }

    public function test_a_game_cannot_participate_twice(): void
    {
        $gameSession = GameSession::factory()->create();
        $this->postJson("/api/game-sessions/{$gameSession->token}/participation", self::PLAYER)->assertCreated();

        $this->postJson("/api/game-sessions/{$gameSession->token}/participation", self::PLAYER)
            ->assertStatus(409)
            ->assertJson(['message' => 'Ta participation est déjà enregistrée.']);
    }

    public function test_invalid_participation_fields_are_refused(): void
    {
        $gameSession = GameSession::factory()->create();

        $this->postJson("/api/game-sessions/{$gameSession->token}/participation", ['email' => 'camille'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['first_name', 'email', 'consent']);
    }

    public function test_an_unknown_game_returns_404(): void
    {
        $this->postJson('/api/game-sessions/01JC8Z3K4V8Q6R2M5T7W9Y1A3B/participation', self::PLAYER)
            ->assertNotFound()
            ->assertExactJson(['message' => 'Partie introuvable.']);
    }

    public function test_the_final_draw_gives_one_prize_per_email_and_runs_once(): void
    {
        Mail::fake();
        Participation::factory()->count(10)->create(['email' => 'camille@example.com']);
        Participation::factory()->create(['email' => 'alex@example.com']);
        Participation::factory()->create(['email' => 'sam@example.com']);

        $this->artisan('lottery:draw')->assertSuccessful();

        $winners = Participation::whereNotNull('prize_rank')->get();
        $this->assertSame([1, 2, 3], $winners->pluck('prize_rank')->sort()->values()->all());
        $this->assertCount(3, $winners->pluck('email')->unique());
        Mail::assertSent(LotteryWon::class, 3);

        $this->artisan('lottery:draw')->assertFailed();
        $this->assertSame(3, Participation::whereNotNull('prize_rank')->count());
    }
}
