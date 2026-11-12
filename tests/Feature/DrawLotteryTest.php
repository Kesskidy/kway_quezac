<?php

namespace Tests\Feature;

use App\Models\Participation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DrawLotteryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_draws_one_winner_per_prize(): void
    {
        Carbon::setTestNow('2026-12-01 12:00:00');
        Participation::factory()->count(10)->create();

        $this->artisan('lottery:draw')
            ->expectsOutputToContain('Tirage terminé : 3 gagnant(s)')
            ->assertSuccessful();

        $winners = Participation::whereNotNull('prize_rank')->orderBy('prize_rank')->get();
        $this->assertSame([1, 2, 3], $winners->pluck('prize_rank')->all());
        $this->assertTrue($winners->every(fn ($p) => $p->drawn_at->toDateTimeString() === '2026-12-01 12:00:00'));
        $this->assertSame(7, Participation::whereNull('drawn_at')->whereNull('prize_rank')->count());
    }

    public function test_an_email_wins_only_one_prize(): void
    {
        // Camille has many winning codes, two other people have one each: all three must win once.
        Participation::factory()->count(20)->create(['email' => 'camille@example.com']);
        Participation::factory()->create(['email' => 'alex@example.com']);
        Participation::factory()->create(['email' => 'sam@example.com']);

        for ($run = 0; $run < 10; $run++) {
            Participation::query()->update(['prize_rank' => null, 'drawn_at' => null]);

            $this->artisan('lottery:draw')->assertSuccessful();

            $winningEmails = Participation::whereNotNull('prize_rank')->pluck('email');
            $this->assertCount(3, $winningEmails);
            $this->assertEqualsCanonicalizing(
                ['camille@example.com', 'alex@example.com', 'sam@example.com'],
                $winningEmails->all(),
            );
        }
    }

    public function test_a_second_draw_is_refused(): void
    {
        Participation::factory()->count(5)->create();

        $this->artisan('lottery:draw')->assertSuccessful();
        $firstDraw = Participation::whereNotNull('prize_rank')->orderBy('prize_rank')->pluck('id');

        $this->artisan('lottery:draw')
            ->expectsOutputToContain('Le tirage a déjà eu lieu')
            ->assertFailed();

        $this->assertEquals($firstDraw, Participation::whereNotNull('prize_rank')->orderBy('prize_rank')->pluck('id'));
    }

    public function test_prizes_are_left_unawarded_when_there_are_not_enough_people(): void
    {
        Participation::factory()->count(4)->create(['email' => 'camille@example.com']);
        Participation::factory()->create(['email' => 'alex@example.com']);

        $this->artisan('lottery:draw')
            ->expectsOutputToContain('1 lot(s) non attribué(s)')
            ->assertSuccessful();

        $this->assertSame([1, 2], Participation::whereNotNull('prize_rank')->orderBy('prize_rank')->pluck('prize_rank')->all());
    }

    public function test_nothing_happens_without_participations(): void
    {
        $this->artisan('lottery:draw')
            ->expectsOutputToContain('Aucune participation')
            ->assertFailed();
    }
}
