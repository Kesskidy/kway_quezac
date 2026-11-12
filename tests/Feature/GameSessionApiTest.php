<?php

namespace Tests\Feature;

use App\Models\GameSession;
use App\Models\PromoCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class GameSessionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_valid_code_is_consumed_and_the_draw_is_returned_and_saved(): void
    {
        $promoCode = PromoCode::factory()->create(['code' => 'QZK-7F3A-92XC']);

        $response = $this->postJson('/api/game-sessions', ['code' => 'QZK-7F3A-92XC']);

        $response->assertCreated()->assertExactJsonStructure(['data' => ['token', 'won', 'scenario']]);
        $this->assertIsBool($response->json('data.won'));
        $this->assertIsInt($response->json('data.scenario'));

        $gameSession = GameSession::firstOrFail();
        $this->assertSame($response->json('data.token'), $gameSession->token);
        $this->assertSame(26, strlen($gameSession->token)); // ULID
        $this->assertSame($response->json('data.won'), $gameSession->won);
        $this->assertSame($response->json('data.scenario'), $gameSession->scenario);
        $this->assertTrue($gameSession->promoCode->is($promoCode));
        $this->assertNotNull($promoCode->fresh()->used_at);
    }

    public function test_probability_100_always_wins(): void
    {
        config(['game.win_probability' => 100]);
        PromoCode::factory()->create(['code' => 'QZK-AAAA-AAAA']);

        $this->postJson('/api/game-sessions', ['code' => 'QZK-AAAA-AAAA'])
            ->assertCreated()
            ->assertJsonPath('data.won', true);
    }

    public function test_probability_0_always_loses(): void
    {
        config(['game.win_probability' => 0]);
        PromoCode::factory()->create(['code' => 'QZK-AAAA-AAAA']);

        $this->postJson('/api/game-sessions', ['code' => 'QZK-AAAA-AAAA'])
            ->assertCreated()
            ->assertJsonPath('data.won', false);
    }

    public function test_the_api_never_exposes_the_probability_or_the_prizes(): void
    {
        PromoCode::factory()->create(['code' => 'QZK-AAAA-AAAA']);

        $response = $this->postJson('/api/game-sessions', ['code' => 'QZK-AAAA-AAAA'])->assertCreated();

        $this->assertSame(['token', 'won', 'scenario'], array_keys($response->json('data')));
    }

    public function test_the_code_is_normalized(): void
    {
        PromoCode::factory()->create(['code' => 'QZK-7F3A-92XC']);

        $this->postJson('/api/game-sessions', ['code' => '  qzk-7f3a-92xc '])->assertCreated();
    }

    public function test_a_code_can_only_be_used_once(): void
    {
        PromoCode::factory()->create(['code' => 'QZK-7F3A-92XC']);

        $first = $this->postJson('/api/game-sessions', ['code' => 'QZK-7F3A-92XC'])->assertCreated();

        $this->postJson('/api/game-sessions', ['code' => 'QZK-7F3A-92XC'])
            ->assertStatus(409)
            ->assertExactJson([
                'message' => 'Ce code a déjà été utilisé.',
                'errors' => ['code' => ['Ce code a déjà été utilisé.']],
            ]);

        // One code = one draw: the first result is kept.
        $this->assertSame(1, GameSession::count());
        $this->assertSame($first->json('data.won'), GameSession::first()->won);
    }

    public function test_an_unknown_code_is_rejected(): void
    {
        $this->postJson('/api/game-sessions', ['code' => 'NOPE'])
            ->assertUnprocessable()
            ->assertExactJson([
                'message' => 'Ce code est invalide.',
                'errors' => ['code' => ['Ce code est invalide.']],
            ]);

        $this->assertSame(0, GameSession::count());
    }

    public function test_a_missing_code_is_rejected(): void
    {
        $this->postJson('/api/game-sessions', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['code' => 'Saisis ton code pour jouer.']);
    }

    public function test_a_code_longer_than_32_characters_is_rejected(): void
    {
        $this->postJson('/api/game-sessions', ['code' => str_repeat('A', 33)])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['code' => 'Ce code est invalide.']);
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

    public function test_version_1_routes_are_gone(): void
    {
        $gameSession = GameSession::factory()->create();

        $this->getJson("/api/game-sessions/{$gameSession->token}/course")->assertNotFound();
        $this->postJson("/api/game-sessions/{$gameSession->token}/finish", ['inputs' => []])->assertNotFound();
    }

    public function test_game_sessions_table_matches_the_contract(): void
    {
        $this->assertEqualsCanonicalizing(
            ['id', 'token', 'promo_code_id', 'won', 'scenario', 'created_at', 'updated_at'],
            Schema::getColumnListing('game_sessions'),
        );
    }
}
