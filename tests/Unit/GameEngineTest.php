<?php

namespace Tests\Unit;

use App\Game\GameEngine;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class GameEngineTest extends TestCase
{
    private GameEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();

        // Config fixe : les tests ne dépendent pas des réglages de config/jeu.php.
        $this->engine = new GameEngine([
            'vitesse' => ['max' => 1000, 'min' => 500],
            'main' => ['mouillure_max' => 100, 'gain_par_intensite' => 1, 'sechage_par_tick' => 2],
            'bouteille' => ['capacite' => 1000, 'seuil_victoire_pourcent' => 10],
        ]);
    }

    /**
     * Parcours avec une pluie constante (0 = pas de pluie).
     */
    private function parcours(int $distance, int $intensite = 0, array $arbres = [], int $dureeMax = 10_000): array
    {
        return [
            'distance' => $distance,
            'duree_max_ticks' => $dureeMax,
            'phases' => [['debut' => 0, 'fin' => $dureeMax, 'type' => 'test', 'intensite' => $intensite]],
            'arbres' => $arbres,
        ];
    }

    public function test_sans_pluie_le_personnage_court_a_vitesse_max(): void
    {
        $resultat = $this->engine->simuler($this->parcours(10), []);

        $this->assertSame(10, $resultat->ticks);
        $this->assertTrue($resultat->arrive);
        $this->assertSame(0, $resultat->remplissage);
        $this->assertTrue($resultat->victoire);
    }

    public function test_sans_proteger_la_bouteille_elle_se_remplit(): void
    {
        $resultat = $this->engine->simuler($this->parcours(100, intensite: 2), []);

        $this->assertSame(100, $resultat->ticks);
        $this->assertSame(200, $resultat->remplissage);
        $this->assertSame(20.0, $resultat->pourcentage());
        $this->assertFalse($resultat->victoire);
    }

    public function test_la_main_sur_la_bouteille_empeche_le_remplissage_mais_ralentit(): void
    {
        $resultat = $this->engine->simuler(
            $this->parcours(100, intensite: 2),
            [['tick' => 0, 'type' => 'appui']],
        );

        $this->assertSame(0, $resultat->remplissage);
        $this->assertSame(100, $resultat->mouillureFinale);
        // 50 ticks à vitesse décroissante (37,25 m), puis 126 ticks à 0,5 m/tick.
        $this->assertSame(176, $resultat->ticks);
        $this->assertTrue($resultat->victoire);
    }

    public function test_la_vitesse_baisse_avec_la_mouillure(): void
    {
        $this->assertSame(1000, $this->engine->vitesse(0));
        $this->assertSame(750, $this->engine->vitesse(50));
        $this->assertSame(500, $this->engine->vitesse(100));
    }

    public function test_la_main_seche_dans_le_kway_et_la_bouteille_se_remplit(): void
    {
        $resultat = $this->engine->simuler($this->parcours(30, intensite: 2), [
            ['tick' => 0, 'type' => 'appui'],
            ['tick' => 10, 'type' => 'relache'],
        ]);

        // Mouillure 20 au tick 10, puis -2 par tick : sèche au bout de 10 ticks.
        $this->assertSame(0, $resultat->mouillureFinale);
        $this->assertSame(31, $resultat->ticks);
        // Bouteille ouverte pendant les ticks 10 à 30 : 21 ticks x 2.
        $this->assertSame(42, $resultat->remplissage);
    }

    public function test_la_main_ne_se_mouille_pas_sans_pluie(): void
    {
        $resultat = $this->engine->simuler(
            $this->parcours(10),
            [['tick' => 0, 'type' => 'appui']],
        );

        $this->assertSame(0, $resultat->mouillureFinale);
        $this->assertSame(10, $resultat->ticks);
    }

    public function test_il_ne_pleut_pas_sous_un_arbre(): void
    {
        $resultat = $this->engine->simuler(
            $this->parcours(10, intensite: 2, arbres: [['debut' => 0, 'fin' => 5]]),
            [],
        );

        // Ticks 0 à 4 sous l'arbre (0 à 5 m), ticks 5 à 9 sous la pluie.
        $this->assertSame(10, $resultat->remplissage);
    }

    public function test_l_intensite_suit_les_phases(): void
    {
        $parcours = [
            'distance' => 10,
            'duree_max_ticks' => 100,
            'phases' => [
                ['debut' => 0, 'fin' => 5, 'type' => 'bruine', 'intensite' => 1],
                ['debut' => 5, 'fin' => 100, 'type' => 'orage', 'intensite' => 4],
            ],
            'arbres' => [],
        ];

        $resultat = $this->engine->simuler($parcours, []);

        $this->assertSame(5 * 1 + 5 * 4, $resultat->remplissage);
    }

    public function test_la_bouteille_ne_deborde_pas(): void
    {
        $resultat = $this->engine->simuler($this->parcours(300, intensite: 4), []);

        $this->assertSame(1000, $resultat->remplissage);
        $this->assertSame(100.0, $resultat->pourcentage());
    }

    public function test_victoire_strictement_sous_10_pourcent(): void
    {
        $juste = $this->engine->simuler($this->parcours(99, intensite: 1), []);
        $pile = $this->engine->simuler($this->parcours(100, intensite: 1), []);

        $this->assertSame(99, $juste->remplissage);
        $this->assertTrue($juste->victoire);

        $this->assertSame(100, $pile->remplissage);
        $this->assertFalse($pile->victoire);
    }

    public function test_pas_de_victoire_sans_franchir_l_arrivee(): void
    {
        $resultat = $this->engine->simuler($this->parcours(10, dureeMax: 5), []);

        $this->assertSame(5, $resultat->ticks);
        $this->assertFalse($resultat->arrive);
        $this->assertFalse($resultat->victoire);
    }

    public function test_les_entrees_apres_l_arrivee_sont_ignorees(): void
    {
        $resultat = $this->engine->simuler(
            $this->parcours(10, intensite: 2),
            [['tick' => 500, 'type' => 'appui']],
        );

        $this->assertSame(10, $resultat->ticks);
        $this->assertSame(20, $resultat->remplissage);
    }

    public function test_le_resultat_est_identique_a_chaque_rejeu(): void
    {
        $entrees = [
            ['tick' => 3, 'type' => 'appui'],
            ['tick' => 40, 'type' => 'relache'],
            ['tick' => 55, 'type' => 'appui'],
        ];

        $a = $this->engine->simuler($this->parcours(100, intensite: 3), $entrees);
        $b = $this->engine->simuler($this->parcours(100, intensite: 3), $entrees);

        $this->assertEquals($a, $b);
    }

    public static function entreesIncoherentes(): array
    {
        return [
            'commence par un relâchement' => [[['tick' => 0, 'type' => 'relache']]],
            'deux appuis de suite' => [[['tick' => 0, 'type' => 'appui'], ['tick' => 5, 'type' => 'appui']]],
            'ticks non croissants' => [[['tick' => 5, 'type' => 'appui'], ['tick' => 5, 'type' => 'relache']]],
            'tick négatif' => [[['tick' => -1, 'type' => 'appui']]],
            'tick non entier' => [[['tick' => '3', 'type' => 'appui']]],
            'type inconnu' => [[['tick' => 0, 'type' => 'saut']]],
            'tick manquant' => [[['type' => 'appui']]],
        ];
    }

    #[DataProvider('entreesIncoherentes')]
    public function test_les_entrees_incoherentes_sont_refusees(array $entrees): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->engine->simuler($this->parcours(10), $entrees);
    }
}
