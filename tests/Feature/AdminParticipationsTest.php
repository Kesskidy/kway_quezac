<?php

namespace Tests\Feature;

use App\Models\Participation;
use App\Models\PromoCode;
use App\Models\GameSession;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminParticipationsTest extends TestCase
{
    use RefreshDatabase;

    private const EMAIL = 'admin@example.com';

    private const PASSWORD = 'mot-de-passe-solide';

    private function seedAdmin(): void
    {
        config(['auth.admin.email' => self::EMAIL, 'auth.admin.password' => self::PASSWORD]);
        $this->seed(AdminUserSeeder::class);
    }

    private function asAdmin(): array
    {
        return ['Authorization' => 'Basic '.base64_encode(self::EMAIL.':'.self::PASSWORD)];
    }

    public static function adminUrls(): array
    {
        return [
            'list' => ['/admin/participations'],
            'export' => ['/admin/participations/export'],
        ];
    }

    #[DataProvider('adminUrls')]
    public function test_admin_pages_require_credentials(string $url): void
    {
        $this->seedAdmin();

        $this->get($url)->assertUnauthorized()->assertHeader('WWW-Authenticate');
    }

    #[DataProvider('adminUrls')]
    public function test_a_wrong_password_is_refused(string $url): void
    {
        $this->seedAdmin();

        $this->get($url, ['Authorization' => 'Basic '.base64_encode(self::EMAIL.':wrong-password')])
            ->assertUnauthorized();
    }

    public function test_the_admin_sees_the_participations(): void
    {
        $this->seedAdmin();
        $promoCode = PromoCode::factory()->used()->create(['code' => 'QZK-AAAA-BBBB']);
        $winner = Participation::factory()
            ->for(GameSession::factory()->won()->for($promoCode))
            ->create(['first_name' => 'Camille', 'prize_rank' => 1, 'drawn_at' => now()]);
        Participation::factory()->create(['first_name' => 'Alex']);

        $this->get('/admin/participations', $this->asAdmin())
            ->assertOk()
            ->assertSee('Camille')
            ->assertSee($winner->email)
            ->assertSee('QZK-AAAA-BBBB')
            ->assertSee(config('game.prizes')[1])
            ->assertSee('Alex')
            ->assertSeeInOrder(['Camille', 'Alex']) // winners first
            ->assertSee('Exporter en CSV')
            ->assertSee('noindex', false);
    }

    public function test_the_list_is_paginated(): void
    {
        $this->seedAdmin();
        Participation::factory()->count(51)->create();

        $this->get('/admin/participations', $this->asAdmin())
            ->assertOk()
            ->assertSee('Page 1 sur 2')
            ->assertSee('Suivant →');

        $this->get('/admin/participations?page=2', $this->asAdmin())
            ->assertOk()
            ->assertSee('Page 2 sur 2');
    }

    public function test_the_list_shows_an_empty_state(): void
    {
        $this->seedAdmin();

        $this->get('/admin/participations', $this->asAdmin())
            ->assertOk()
            ->assertSee('Aucune participation pour le moment.');
    }

    public function test_the_admin_exports_a_csv(): void
    {
        $this->seedAdmin();
        Participation::factory()->create(['first_name' => 'Camille', 'email' => 'camille@example.com', 'prize_rank' => 2, 'drawn_at' => now()]);
        Participation::factory()->create(['first_name' => 'Alex', 'email' => 'alex@example.com']);

        $response = $this->get('/admin/participations/export', $this->asAdmin());

        $response->assertOk()->assertDownload();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));

        $csv = $response->streamedContent();
        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv); // BOM for Excel
        $lines = array_map(fn ($line) => str_getcsv($line, ';'), array_filter(explode("\n", trim(substr($csv, 3)))));

        $this->assertSame(['ID', 'Prénom', 'E-mail', 'Code', 'Participation le', 'Consentement le', 'Rang du lot', 'Lot', 'Tiré le'], $lines[0]);
        $this->assertCount(3, $lines);
        $this->assertSame(['Camille', 'camille@example.com'], array_slice($lines[1], 1, 2));
        $this->assertSame(['2', config('game.prizes')[2]], array_slice($lines[1], 6, 2));
        $this->assertSame(['', ''], array_slice($lines[2], 6, 2));
    }

    public function test_the_export_neutralizes_spreadsheet_formulas(): void
    {
        $this->seedAdmin();
        Participation::factory()->create(['first_name' => '=HYPERLINK("http://evil.example")']);

        $csv = $this->get('/admin/participations/export', $this->asAdmin())->streamedContent();

        $this->assertStringContainsString("'=HYPERLINK", $csv);
    }

    public function test_the_admin_seeder_creates_the_account_from_the_env(): void
    {
        $this->seedAdmin();
        $this->seedAdmin(); // running it twice updates the same account

        $this->assertSame(1, User::count());
        $admin = User::firstOrFail();
        $this->assertSame(self::EMAIL, $admin->email);
        $this->assertTrue(Hash::check(self::PASSWORD, $admin->password));
    }

    public static function invalidAdminConfig(): array
    {
        return [
            'missing email' => [null, 'mot-de-passe-solide'],
            'missing password' => ['admin@example.com', null],
            'password too short' => ['admin@example.com', 'court'],
        ];
    }

    #[DataProvider('invalidAdminConfig')]
    public function test_the_admin_seeder_refuses_an_incomplete_env(?string $email, ?string $password): void
    {
        config(['auth.admin.email' => $email, 'auth.admin.password' => $password]);

        $this->seed(AdminUserSeeder::class);

        $this->assertSame(0, User::count());
    }

    public function test_the_database_seeder_creates_no_account_with_a_known_password(): void
    {
        config(['auth.admin.email' => null, 'auth.admin.password' => null]);

        $this->seed(DatabaseSeeder::class);

        $this->assertSame(0, User::count());
    }
}
