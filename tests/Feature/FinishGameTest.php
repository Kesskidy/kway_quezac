<?php

namespace Tests\Feature;

use App\Models\GameSession;
use App\Services\Game\GameEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FinishGameTest extends TestCase
{
    use RefreshDatabase;

    private const DRY_COURSE = ['phases' => [], 'trees' => []];

    private const RAINY_COURSE = [
        'phases' => [['type' => 'rain', 'start_tick' => 0, 'end_tick' => 4000]],
        'trees' => [],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-11-10 15:00:00');
    }

    /**
     * Game started long enough ago to pass the real-time check.
     */
    private function startedGame(array $course = self::DRY_COURSE, string $startedAt = '2026-11-10 14:55:00'): GameSession
    {
        return GameSession::factory()->create(['course' => $course, 'started_at' => $startedAt]);
    }

    private function finish(GameSession $gameSession, mixed $inputs = [])
    {
        return $this->postJson("/api/game-sessions/{$gameSession->token}/finish", ['inputs' => $inputs]);
    }

    public function test_the_server_replays_the_inputs_and_saves_the_result(): void
    {
        $gameSession = $this->startedGame(self::RAINY_COURSE);
        $inputs = [
            ['tick' => 0, 'action' => 'cover'],
            ['tick' => 2900, 'action' => 'uncover'],
        ];

        $expected = (new GameEngine)->simulate(self::RAINY_COURSE, $inputs);

        $this->finish($gameSession, $inputs)
            ->assertOk()
            ->assertExactJson(['data' => [
                'fill_percent' => $expected->fillPercent,
                'duration_ticks' => $expected->durationTicks,
                'wheel_allowed' => $expected->wheelAllowed,
            ]]);

        $gameSession->refresh();
        $this->assertTrue($gameSession->isFinished());
        $this->assertSame($inputs, $gameSession->inputs);
        $this->assertSame($expected->fillPercent, $gameSession->fill_percent);
        $this->assertSame($expected->durationTicks, $gameSession->duration_ticks);
        $this->assertSame($expected->wheelAllowed, $gameSession->wheel_allowed);
    }

    public function test_dry_game_allows_the_wheel(): void
    {
        $this->finish($this->startedGame())
            ->assertOk()
            ->assertExactJson(['data' => ['fill_percent' => 0, 'duration_ticks' => 2000, 'wheel_allowed' => true]]);
    }

    public function test_full_bottle_does_not_allow_the_wheel(): void
    {
        $gameSession = $this->startedGame(self::RAINY_COURSE);

        $this->finish($gameSession)
            ->assertOk()
            ->assertJsonPath('data.fill_percent', 100)
            ->assertJsonPath('data.wheel_allowed', false);

        $this->assertFalse($gameSession->fresh()->wheel_allowed);
    }

    public function test_a_game_cannot_be_finished_twice(): void
    {
        $gameSession = $this->startedGame();

        $this->finish($gameSession)->assertOk();

        $this->finish($gameSession, [['tick' => 0, 'action' => 'cover']])
            ->assertStatus(409)
            ->assertJson(['message' => 'Cette partie est déjà terminée.']);

        $this->assertSame([], $gameSession->fresh()->inputs);
    }

    public function test_a_game_finished_too_fast_is_rejected(): void
    {
        // Dry course = 2000 ticks = 100 s; minimum 100 x 0.9 = 90 s. Started 89 s ago.
        $gameSession = $this->startedGame(startedAt: '2026-11-10 14:58:31');

        $this->finish($gameSession)
            ->assertUnprocessable()
            ->assertExactJson([
                'message' => 'Partie terminée trop vite.',
                'errors' => ['inputs' => ['Partie terminée trop vite.']],
            ]);

        $this->assertFalse($gameSession->fresh()->isFinished());
    }

    public function test_a_game_finished_exactly_at_the_minimum_time_is_accepted(): void
    {
        $this->finish($this->startedGame(startedAt: '2026-11-10 14:58:30'))->assertOk();
    }

    public function test_a_game_that_never_started_is_rejected(): void
    {
        $gameSession = GameSession::factory()->create(['course' => self::DRY_COURSE]);

        $this->finish($gameSession)
            ->assertUnprocessable()
            ->assertJson(['message' => 'Cette partie n’a pas encore commencé.']);
    }

    public function test_unknown_game_returns_404(): void
    {
        $this->postJson('/api/game-sessions/01JC8Z3K4V8Q6R2M5T7W9Y1A3B/finish', ['inputs' => []])
            ->assertNotFound()
            ->assertExactJson(['message' => 'Partie introuvable.']);
    }

    public static function invalidInputs(): array
    {
        return [
            'missing inputs' => [null, 'inputs'],
            'not an array' => ['cover', 'inputs'],
            'not a list' => [['a' => ['tick' => 0, 'action' => 'cover']], 'inputs'],
            'starts with uncover' => [[['tick' => 0, 'action' => 'uncover']], 'inputs.0.action'],
            'two covers in a row' => [[['tick' => 0, 'action' => 'cover'], ['tick' => 5, 'action' => 'cover']], 'inputs.1.action'],
            'ticks not increasing' => [[['tick' => 5, 'action' => 'cover'], ['tick' => 5, 'action' => 'uncover']], 'inputs.1.tick'],
            'negative tick' => [[['tick' => -1, 'action' => 'cover']], 'inputs.0.tick'],
            'tick equal to max_ticks' => [[['tick' => 4000, 'action' => 'cover']], 'inputs.0.tick'],
            'decimal tick' => [[['tick' => 1.5, 'action' => 'cover']], 'inputs.0.tick'],
            'unknown action' => [[['tick' => 0, 'action' => 'jump']], 'inputs.0.action'],
            'missing action' => [[['tick' => 0]], 'inputs.0.action'],
            'extra key (score)' => [[['tick' => 0, 'action' => 'cover', 'score' => 0]], 'inputs.0'],
        ];
    }

    #[DataProvider('invalidInputs')]
    public function test_invalid_inputs_are_rejected(mixed $inputs, string $errorKey): void
    {
        $gameSession = $this->startedGame();

        $this->finish($gameSession, $inputs)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$errorKey]);

        $this->assertFalse($gameSession->fresh()->isFinished());
    }

    public function test_more_than_max_inputs_is_rejected(): void
    {
        config(['game.max_inputs' => 2]);

        $this->finish($this->startedGame(), [
            ['tick' => 0, 'action' => 'cover'],
            ['tick' => 1, 'action' => 'uncover'],
            ['tick' => 2, 'action' => 'cover'],
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['inputs' => 'Trop d’entrées pour une seule partie.']);
    }

    public function test_finish_is_throttled(): void
    {
        $gameSession = $this->startedGame();

        for ($i = 0; $i < 10; $i++) {
            $this->finish($gameSession, 'invalid')->assertUnprocessable();
        }

        $this->finish($gameSession, 'invalid')->assertTooManyRequests();
    }
}
