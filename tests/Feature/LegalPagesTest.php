<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public static function pages(): array
    {
        return [
            'rules' => ['/reglement', 'legal.rules', 'Règlement du jeu'],
            'notice' => ['/mentions-legales', 'legal.notice', 'Mentions légales'],
            'privacy' => ['/confidentialite', 'legal.privacy', 'Politique de confidentialité'],
        ];
    }

    #[DataProvider('pages')]
    public function test_each_legal_page_is_reachable_and_marked_as_fictitious(string $url, string $route, string $title): void
    {
        $this->assertSame(url($url), route($route));

        $this->get($url)
            ->assertOk()
            ->assertSee("<h1", false)
            ->assertSee($title)
            ->assertSee('Projet fictif')
            ->assertSee('sans lien avec les marques K-Way et Quézac')
            ->assertSee(route('legal.rules'), false)
            ->assertSee(route('legal.notice'), false)
            ->assertSee(route('legal.privacy'), false);
    }

    public function test_the_rules_give_dates_the_one_code_rule_prizes_and_data_use(): void
    {
        config([
            'legal.game_starts_on' => '2027-03-01',
            'legal.game_ends_on' => '2027-05-31',
            'legal.final_draw_on' => '2027-06-15',
        ]);

        $response = $this->get('/reglement')->assertOk();

        // regle.md, section 10: dates, "1 code = 1 tirage", prizes, final draw date, personal data.
        $response->assertSee('1 mars 2027')
            ->assertSee('31 mai 2027')
            ->assertSee('15 juin 2027')
            ->assertSee('1 code = 1 tirage')
            ->assertSee('qu’un seul lot', false)
            ->assertSee('Données personnelles');

        foreach (config('game.prizes') as $prize) {
            $response->assertSee($prize);
        }
    }

    public function test_the_privacy_page_quotes_the_consent_text_and_the_contact(): void
    {
        config(['legal.contact_email' => 'contact@example.com']);

        $this->get('/confidentialite')
            ->assertOk()
            ->assertSee('J’accepte le règlement du jeu et l’utilisation de mon e-mail pour être contacté(e) si je gagne.', false)
            ->assertSee('contact@example.com');
    }
}
