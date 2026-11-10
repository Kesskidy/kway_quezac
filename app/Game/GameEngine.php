<?php

namespace App\Game;

use InvalidArgumentException;

/**
 * Rejoue une partie tick par tick à partir du parcours et des entrées du joueur.
 *
 * Déroulé d'un tick t :
 *  1. on applique les entrées du tick t (appui = main sur la bouteille, relache = main dans le K-Way) ;
 *  2. pluie reçue = intensité de la phase en cours, ou 0 si le personnage est sous un arbre ;
 *  3. main sur la bouteille : la main se mouille (pluie x gain), rien ne rentre dans la bouteille ;
 *     main dans le K-Way    : la main sèche, la pluie tombe dans la bouteille ;
 *  4. le personnage avance d'une vitesse qui baisse avec la mouillure de la main.
 *
 * La partie s'arrête à la ligne d'arrivée (ou à duree_max_ticks).
 * Tous les calculs sont en entiers pour pouvoir être reproduits à l'identique en JS.
 */
class GameEngine
{
    public const APPUI = 'appui';

    public const RELACHE = 'relache';

    /** @var array<string, mixed> */
    private array $config;

    /**
     * @param  array<string, mixed>|null  $config  Par défaut config('jeu').
     */
    public function __construct(?array $config = null)
    {
        $this->config = $config ?? config('jeu');
    }

    /**
     * @param  array{distance: int, duree_max_ticks: int, phases: list<array{debut: int, fin: int, intensite: int}>, arbres: list<array{debut: int, fin: int}>}  $parcours
     * @param  list<array{tick: int, type: string}>  $entrees  Appuis et relâchements, dans l'ordre.
     *
     * @throws InvalidArgumentException si les entrées sont incohérentes.
     */
    public function simuler(array $parcours, array $entrees): ResultatPartie
    {
        $entreesParTick = $this->indexerEntrees($entrees);

        $arriveeMm = $parcours['distance'] * 1000;
        $dureeMax = $parcours['duree_max_ticks'];
        $capacite = $this->config['bouteille']['capacite'];

        $position = 0;       // en millimètres
        $mouillure = 0;
        $remplissage = 0;
        $mainSurBouteille = false;
        $tick = 0;

        while ($position < $arriveeMm && $tick < $dureeMax) {
            if (isset($entreesParTick[$tick])) {
                $mainSurBouteille = $entreesParTick[$tick] === self::APPUI;
            }

            $pluie = $this->sousArbre($parcours['arbres'], $position)
                ? 0
                : $this->intensite($parcours['phases'], $tick);

            if ($mainSurBouteille) {
                $mouillure = min(
                    $this->config['main']['mouillure_max'],
                    $mouillure + $pluie * $this->config['main']['gain_par_intensite'],
                );
            } else {
                $mouillure = max(0, $mouillure - $this->config['main']['sechage_par_tick']);
                $remplissage = min($capacite, $remplissage + $pluie);
            }

            $position += $this->vitesse($mouillure);
            $tick++;
        }

        return new ResultatPartie(
            ticks: $tick,
            arrive: $position >= $arriveeMm,
            remplissage: $remplissage,
            capacite: $capacite,
            mouillureFinale: $mouillure,
            victoire: $position >= $arriveeMm && $this->sousLeSeuil($remplissage, $capacite),
        );
    }

    /**
     * Vitesse en mm/tick : de vitesse.max (main sèche) à vitesse.min (main trempée).
     */
    public function vitesse(int $mouillure): int
    {
        $max = $this->config['vitesse']['max'];
        $min = $this->config['vitesse']['min'];

        return $max - intdiv(($max - $min) * $mouillure, $this->config['main']['mouillure_max']);
    }

    private function sousLeSeuil(int $remplissage, int $capacite): bool
    {
        return $remplissage * 100 < $this->config['bouteille']['seuil_victoire_pourcent'] * $capacite;
    }

    /**
     * @param  list<array{debut: int, fin: int, intensite: int}>  $phases
     */
    private function intensite(array $phases, int $tick): int
    {
        foreach ($phases as $phase) {
            if ($tick >= $phase['debut'] && $tick < $phase['fin']) {
                return $phase['intensite'];
            }
        }

        return 0;
    }

    /**
     * @param  list<array{debut: int, fin: int}>  $arbres  En mètres.
     */
    private function sousArbre(array $arbres, int $positionMm): bool
    {
        foreach ($arbres as $arbre) {
            if ($positionMm >= $arbre['debut'] * 1000 && $positionMm < $arbre['fin'] * 1000) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifie les entrées et les range par tick.
     * Règles : ticks entiers >= 0, strictement croissants, en alternance en commençant par un appui.
     *
     * @param  list<array{tick: int, type: string}>  $entrees
     * @return array<int, string>
     */
    private function indexerEntrees(array $entrees): array
    {
        $parTick = [];
        $precedentTick = -1;
        $attendu = self::APPUI;

        foreach (array_values($entrees) as $i => $entree) {
            $tick = $entree['tick'] ?? null;
            $type = $entree['type'] ?? null;

            if (! is_int($tick) || $tick < 0) {
                throw new InvalidArgumentException("Entrée {$i} : tick invalide.");
            }
            if ($tick <= $precedentTick) {
                throw new InvalidArgumentException("Entrée {$i} : les ticks doivent être strictement croissants.");
            }
            if ($type !== $attendu) {
                throw new InvalidArgumentException("Entrée {$i} : « {$attendu} » attendu.");
            }

            $parTick[$tick] = $type;
            $precedentTick = $tick;
            $attendu = $type === self::APPUI ? self::RELACHE : self::APPUI;
        }

        return $parTick;
    }
}
