<?php

namespace App\Services;

use Random\Engine\Mt19937;
use Random\Randomizer;

/**
 * Génère le parcours d'une partie (phases de pluie et arbres).
 *
 * La génération est déterministe : un même seed donne toujours le même
 * parcours, ce qui permet au GameEngine de le régénérer en fin de partie.
 */
class ParcoursGenerator
{
    /**
     * @return array{seed: int, tick_ms: int, duree_ticks: int, phases: list<array{debut: int, fin: int, type: string, intensite: int}>, arbres: list<array{debut: int, fin: int}>}
     */
    public function generer(?int $seed = null): array
    {
        $seed ??= random_int(0, 2 ** 31 - 1);
        $random = new Randomizer(new Mt19937($seed));
        $duree = (int) config('jeu.duree_ticks');

        return [
            'seed' => $seed,
            'tick_ms' => (int) config('jeu.tick_ms'),
            'duree_ticks' => $duree,
            'phases' => $this->genererPhases($random, $duree),
            'arbres' => $this->genererArbres($random, $duree),
        ];
    }

    /**
     * Phases consécutives qui couvrent toute la partie, sans deux types identiques à la suite.
     */
    private function genererPhases(Randomizer $random, int $duree): array
    {
        $types = config('jeu.phases.types');
        $noms = array_keys($types);
        $phases = [];
        $tick = 0;
        $precedent = null;

        while ($tick < $duree) {
            $choix = array_values(array_diff($noms, [$precedent])) ?: $noms;
            $type = $choix[$random->getInt(0, count($choix) - 1)];

            $fin = min($duree, $tick + $random->getInt(
                config('jeu.phases.duree_min'),
                config('jeu.phases.duree_max'),
            ));

            $phases[] = [
                'debut' => $tick,
                'fin' => $fin,
                'type' => $type,
                'intensite' => $types[$type]['intensite'],
            ];

            $tick = $fin;
            $precedent = $type;
        }

        return $phases;
    }

    /**
     * Arbres espacés qui ne se chevauchent pas et se terminent avant la fin de la partie.
     */
    private function genererArbres(Randomizer $random, int $duree): array
    {
        $config = config('jeu.arbres');
        $arbres = [];
        $tick = $config['premier_apres'];

        while (true) {
            $debut = $tick + $random->getInt($config['ecart_min'], $config['ecart_max']);
            $fin = $debut + $random->getInt($config['duree_min'], $config['duree_max']);

            if ($fin >= $duree) {
                break;
            }

            $arbres[] = ['debut' => $debut, 'fin' => $fin];
            $tick = $fin;
        }

        return $arbres;
    }
}
