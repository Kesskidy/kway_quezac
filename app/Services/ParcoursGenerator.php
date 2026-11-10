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
     * - phases : en ticks, couvrent toute la durée maximale d'une partie ;
     * - arbres : en mètres, le long de la course.
     *
     * @return array{seed: int, tick_ms: int, distance: int, duree_max_ticks: int, phases: list<array{debut: int, fin: int, type: string, intensite: int}>, arbres: list<array{debut: int, fin: int}>}
     */
    public function generer(?int $seed = null): array
    {
        $seed ??= random_int(0, 2 ** 31 - 1);
        $random = new Randomizer(new Mt19937($seed));
        $dureeMax = (int) config('jeu.duree_max_ticks');
        $distance = (int) config('jeu.distance');

        return [
            'seed' => $seed,
            'tick_ms' => (int) config('jeu.tick_ms'),
            'distance' => $distance,
            'duree_max_ticks' => $dureeMax,
            'phases' => $this->genererPhases($random, $dureeMax),
            'arbres' => $this->genererArbres($random, $distance),
        ];
    }

    /**
     * Phases consécutives (en ticks), sans deux types identiques à la suite.
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
     * Arbres (en mètres) espacés, sans chevauchement, avant la ligne d'arrivée.
     */
    private function genererArbres(Randomizer $random, int $distance): array
    {
        $config = config('jeu.arbres');
        $arbres = [];
        $metre = $config['premier_apres'];

        while (true) {
            $debut = $metre + $random->getInt($config['ecart_min'], $config['ecart_max']);
            $fin = $debut + $random->getInt($config['longueur_min'], $config['longueur_max']);

            if ($fin >= $distance) {
                break;
            }

            $arbres[] = ['debut' => $debut, 'fin' => $fin];
            $metre = $fin;
        }

        return $arbres;
    }
}
