<?php

namespace Tests\Feature;

use App\Mail\LotteryWon;
use App\Mail\ParticipationConfirmed;
use App\Models\GameSession;
use App\Models\Participation;
use App\Models\PromoCode;
use App\Services\Game\GameDraw;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

/**
 * M12: the whole game, from the code on the bottle to the prize e-mail.
 */
class GameFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_from_the_code_to_the_prize_email(): void
    {
        Mail::fake();
        config(['game.win_probability' => 100]);
        PromoCode::factory()->create(['code' => 'QZK-AAAA-0001']);
        PromoCode::factory()->create(['code' => 'QZK-AAAA-0002']);

        // Camille plays two bottles and wins both draws.
        foreach (['QZK-AAAA-0001', 'QZK-AAAA-0002'] as $code) {
            $token = $this->postJson('/api/game-sessions', ['code' => $code])
                ->assertCreated()
                ->assertJsonPath('data.won', true)
                ->json('data.token');

            $this->postJson("/api/game-sessions/{$token}/participation", [
                'first_name' => 'Camille',
                'email' => 'camille@example.com',
                'consent' => true,
            ])->assertCreated();
        }

        // Each bottle can only be played once.
        $this->postJson('/api/game-sessions', ['code' => 'QZK-AAAA-0001'])->assertStatus(409);

        Mail::assertSent(ParticipationConfirmed::class, 2);
        $this->assertSame(2, Participation::where('email', 'camille@example.com')->count());

        // Two participations, but only one prize for Camille.
        $this->artisan('lottery:draw')->assertSuccessful();

        $this->assertSame(1, Participation::whereNotNull('prize_rank')->count());
        $this->assertSame(1, Participation::whereNotNull('prize_rank')->value('prize_rank'));
        Mail::assertSent(LotteryWon::class, 1);
        Mail::assertSent(LotteryWon::class, fn (LotteryWon $mail) => $mail->hasTo('camille@example.com'));

        // The final draw happens once.
        $this->artisan('lottery:draw')->assertFailed();
        Mail::assertSent(LotteryWon::class, 1);
    }

    public function test_a_lost_game_cannot_reach_the_final_draw(): void
    {
        config(['game.win_probability' => 0]);
        PromoCode::factory()->create(['code' => 'QZK-AAAA-0001']);

        $token = $this->postJson('/api/game-sessions', ['code' => 'QZK-AAAA-0001'])
            ->assertJsonPath('data.won', false)
            ->json('data.token');

        $this->postJson("/api/game-sessions/{$token}/participation", [
            'first_name' => 'Alex',
            'email' => 'alex@example.com',
            'consent' => true,
        ])->assertForbidden();

        $this->artisan('lottery:draw')->expectsOutputToContain('Aucune participation')->assertFailed();
    }

    public function test_the_code_is_not_consumed_if_the_draw_fails(): void
    {
        $promoCode = PromoCode::factory()->create(['code' => 'QZK-AAAA-0001']);
        $this->mock(GameDraw::class)->shouldReceive('draw')->andThrow(new RuntimeException('draw failed'));

        $this->withoutExceptionHandling();

        try {
            $this->postJson('/api/game-sessions', ['code' => 'QZK-AAAA-0001']);
            $this->fail('The draw failure should bubble up.');
        } catch (RuntimeException) {
            // The transaction was rolled back: the player can try the same code again.
        }

        $this->assertNull($promoCode->fresh()->used_at);
        $this->assertSame(0, GameSession::count());
    }
}
