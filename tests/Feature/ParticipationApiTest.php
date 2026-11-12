<?php

namespace Tests\Feature;

use App\Models\GameSession;
use App\Models\Participation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ParticipationApiTest extends TestCase
{
    use RefreshDatabase;

    private const VALID = [
        'first_name' => 'Camille',
        'email' => 'camille@example.com',
        'consent' => true,
    ];

    private function participate(GameSession $gameSession, array $data = self::VALID)
    {
        return $this->postJson("/api/game-sessions/{$gameSession->token}/participation", $data);
    }

    public function test_a_winner_registers_a_participation(): void
    {
        Carbon::setTestNow('2026-11-12 10:00:00');
        $gameSession = GameSession::factory()->won()->create();

        $this->participate($gameSession)
            ->assertCreated()
            ->assertExactJson(['data' => [
                'message' => "C'est noté ! Ta participation au tirage des lots est enregistrée.",
            ]]);

        $participation = $gameSession->participation()->firstOrFail();
        $this->assertSame('Camille', $participation->first_name);
        $this->assertSame('camille@example.com', $participation->email);
        $this->assertSame('2026-11-12 10:00:00', $participation->consented_at->toDateTimeString());
        $this->assertNull($participation->prize_rank);
        $this->assertNull($participation->drawn_at);
    }

    public function test_the_email_is_stored_in_lower_case_without_spaces(): void
    {
        $gameSession = GameSession::factory()->won()->create();

        $this->participate($gameSession, [...self::VALID, 'email' => '  Camille@Example.COM '])->assertCreated();

        $this->assertSame('camille@example.com', $gameSession->participation->email);
    }

    public function test_the_same_email_can_participate_with_several_winning_codes(): void
    {
        $first = GameSession::factory()->won()->create();
        $second = GameSession::factory()->won()->create();

        $this->participate($first)->assertCreated();
        $this->participate($second)->assertCreated();

        $this->assertSame(2, Participation::where('email', 'camille@example.com')->count());
    }

    public function test_a_lost_game_is_refused(): void
    {
        $gameSession = GameSession::factory()->lost()->create();

        $this->participate($gameSession)
            ->assertForbidden()
            ->assertExactJson(['message' => 'Cette partie n’est pas gagnante.']);

        $this->assertSame(0, Participation::count());
    }

    public function test_a_lost_game_is_refused_before_field_validation(): void
    {
        $gameSession = GameSession::factory()->lost()->create();

        $this->participate($gameSession, [])->assertForbidden();
    }

    public function test_a_second_participation_for_the_same_game_is_refused(): void
    {
        $gameSession = GameSession::factory()->won()->create();

        $this->participate($gameSession)->assertCreated();

        $this->participate($gameSession, [...self::VALID, 'email' => 'other@example.com'])
            ->assertStatus(409)
            ->assertExactJson([
                'message' => 'Ta participation est déjà enregistrée.',
                'errors' => ['game_session' => ['Ta participation est déjà enregistrée.']],
            ]);

        $this->assertSame(1, Participation::count());
        $this->assertSame('camille@example.com', Participation::first()->email);
    }

    public static function invalidFields(): array
    {
        return [
            'missing first name' => [['first_name' => null], 'first_name', 'Indique ton prénom.'],
            'first name too long' => [['first_name' => str_repeat('a', 51)], 'first_name', 'Ton prénom doit faire 50 caractères maximum.'],
            'missing email' => [['email' => null], 'email', 'Indique ton adresse e-mail.'],
            'invalid email' => [['email' => 'camille'], 'email', 'Cette adresse e-mail est invalide.'],
            'email too long' => [['email' => str_repeat('a', 250).'@example.com'], 'email', 'Cette adresse e-mail est trop longue.'],
            'consent refused' => [['consent' => false], 'consent', 'Tu dois accepter le règlement pour participer.'],
            'missing consent' => [['consent' => null], 'consent', 'Tu dois accepter le règlement pour participer.'],
        ];
    }

    #[DataProvider('invalidFields')]
    public function test_invalid_fields_are_rejected(array $override, string $field, string $message): void
    {
        $gameSession = GameSession::factory()->won()->create();

        $this->participate($gameSession, array_filter([...self::VALID, ...$override], fn ($value) => $value !== null))
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$field => $message]);

        $this->assertSame(0, Participation::count());
    }

    public function test_unknown_game_returns_404(): void
    {
        $this->postJson('/api/game-sessions/01JC8Z3K4V8Q6R2M5T7W9Y1A3B/participation', self::VALID)
            ->assertNotFound()
            ->assertExactJson(['message' => 'Partie introuvable.']);
    }

    public function test_participation_is_throttled(): void
    {
        $gameSession = GameSession::factory()->won()->create();

        for ($i = 0; $i < 10; $i++) {
            $this->participate($gameSession, [])->assertUnprocessable();
        }

        $this->participate($gameSession, [])->assertTooManyRequests();
    }

    public function test_tables_match_the_contract(): void
    {
        $this->assertEqualsCanonicalizing(
            ['id', 'game_session_id', 'first_name', 'email', 'consented_at', 'prize_rank', 'drawn_at', 'created_at', 'updated_at'],
            Schema::getColumnListing('participations'),
        );
        $this->assertEqualsCanonicalizing(
            ['id', 'name', 'slug', 'description', 'price_cents', 'image_path', 'created_at', 'updated_at'],
            Schema::getColumnListing('products'),
        );
        $this->assertEqualsCanonicalizing(
            ['id', 'product_id', 'first_name', 'email', 'size', 'created_at', 'updated_at'],
            Schema::getColumnListing('orders'),
        );
    }
}
