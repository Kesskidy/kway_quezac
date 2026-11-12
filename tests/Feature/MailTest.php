<?php

namespace Tests\Feature;

use App\Mail\LotteryWon;
use App\Mail\ParticipationConfirmed;
use App\Models\GameSession;
use App\Models\Participation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MailTest extends TestCase
{
    use RefreshDatabase;

    private const VALID = ['first_name' => 'Camille', 'email' => 'camille@example.com', 'consent' => true];

    public function test_a_registered_participation_sends_a_confirmation(): void
    {
        Mail::fake();
        $gameSession = GameSession::factory()->won()->create();

        $this->postJson("/api/game-sessions/{$gameSession->token}/participation", self::VALID)->assertCreated();

        Mail::assertSent(ParticipationConfirmed::class, function (ParticipationConfirmed $mail) {
            return $mail->hasTo('camille@example.com') && $mail->participation->first_name === 'Camille';
        });
        Mail::assertSentCount(1);
    }

    public function test_no_confirmation_when_the_participation_is_refused(): void
    {
        Mail::fake();
        $lost = GameSession::factory()->lost()->create();
        $won = GameSession::factory()->won()->create();
        Participation::factory()->for($won)->create();

        $this->postJson("/api/game-sessions/{$lost->token}/participation", self::VALID)->assertForbidden();
        $this->postJson("/api/game-sessions/{$won->token}/participation", self::VALID)->assertStatus(409);
        $this->postJson("/api/game-sessions/{$won->token}/participation", [])->assertUnprocessable();

        Mail::assertNothingSent();
    }

    public function test_the_final_draw_notifies_each_winner_with_their_prize(): void
    {
        Mail::fake();
        Participation::factory()->count(6)->create();

        $this->artisan('lottery:draw')->assertSuccessful();

        $winners = Participation::whereNotNull('prize_rank')->get();
        $this->assertCount(3, $winners);
        Mail::assertSentCount(3);

        foreach ($winners as $winner) {
            Mail::assertSent(LotteryWon::class, fn (LotteryWon $mail) => $mail->hasTo($winner->email)
                && $mail->participation->is($winner));
        }
    }

    public function test_a_refused_second_draw_sends_nothing(): void
    {
        Participation::factory()->count(6)->create();
        $this->artisan('lottery:draw')->assertSuccessful();

        Mail::fake();
        $this->artisan('lottery:draw')->assertFailed();

        Mail::assertNothingSent();
    }

    public function test_participation_confirmed_content(): void
    {
        $participation = Participation::factory()->make(['first_name' => 'Camille']);

        $mail = new ParticipationConfirmed($participation);

        $mail->assertHasSubject('Ta participation au tirage K-Way × Quézac est enregistrée');
        $mail->assertSeeInHtml('Camille');
        $mail->assertSeeInHtml('tirage final des lots');
        $mail->assertSeeInHtml(url('/reglement'));
        $mail->assertSeeInHtml('Projet étudiant fictif');
    }

    public function test_lottery_won_content_shows_the_prize(): void
    {
        $participation = Participation::factory()->make(['first_name' => 'Camille', 'prize_rank' => 2]);

        $mail = new LotteryWon($participation);

        $mail->assertHasSubject('Tu as gagné un lot K-Way × Quézac !');
        $mail->assertSeeInHtml('Camille');
        $mail->assertSeeInHtml('lot n° 2');
        $mail->assertSeeInHtml(config('game.prizes')[2]);
        $mail->assertSeeInHtml('Projet étudiant fictif');
    }
}
