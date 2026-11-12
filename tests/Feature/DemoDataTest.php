<?php

namespace Tests\Feature;

use App\Models\GameSession;
use App\Models\Participation;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoParticipationSeeder;
use Database\Seeders\PromoCodeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * M14: the demo data (regle.md, section 10).
 */
class DemoDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_promo_code_seeder_creates_unique_unused_codes(): void
    {
        $this->seed(PromoCodeSeeder::class);
        $this->seed(PromoCodeSeeder::class);

        $codes = PromoCode::pluck('code');
        $this->assertCount(2 * PromoCodeSeeder::COUNT, $codes);
        $this->assertCount($codes->count(), $codes->unique());
        $this->assertSame(0, PromoCode::whereNotNull('used_at')->count());

        foreach ($codes as $code) {
            $this->assertMatchesRegularExpression('/^QZK-[A-HJ-NP-Z2-9]{4}-[A-HJ-NP-Z2-9]{4}$/', $code);
        }
    }

    public function test_the_demo_participations_are_ready_for_the_final_draw(): void
    {
        $this->seed(DemoParticipationSeeder::class);

        $this->assertSame(8, Participation::count());
        $this->assertSame(8, GameSession::where('won', true)->count());
        $this->assertSame(0, PromoCode::whereNull('used_at')->count());
        $this->assertSame(2, Participation::where('email', 'camille@example.com')->count());

        $this->artisan('lottery:draw')->assertSuccessful();

        $winners = Participation::whereNotNull('prize_rank')->pluck('email');
        $this->assertCount(3, $winners);
        $this->assertCount(3, $winners->unique());
    }

    public function test_the_full_seed_outside_local_has_no_demo_participations(): void
    {
        config(['auth.admin.email' => 'admin@example.com', 'auth.admin.password' => 'mot-de-passe-solide']);

        $this->seed(DatabaseSeeder::class); // the test environment is not `local`

        $this->assertSame(PromoCodeSeeder::COUNT, PromoCode::count());
        $this->assertSame(1, Product::count());
        $this->assertSame(['admin@example.com'], User::pluck('email')->all());
        $this->assertSame(0, Participation::count());
    }

    public function test_the_full_seed_in_local_adds_the_demo_participations(): void
    {
        $this->app['env'] = 'local';

        $this->seed(DatabaseSeeder::class);

        $this->assertSame(8, Participation::count());
    }
}
