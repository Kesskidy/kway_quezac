<?php

/*
|--------------------------------------------------------------------------
| Paramètres du jeu
|--------------------------------------------------------------------------
|
| Valeurs partagées par le générateur de parcours (/parcours), le moteur
| PHP (GameEngine) et le rendu front-end.
|
| Le personnage court avec une bouteille ouverte sur le dos :
| - appui maintenu  : la main bouche la bouteille, mais se mouille sous la
|                     pluie, ce qui ralentit le personnage ;
| - relâché         : la main est au sec dans le K-Way et sèche, mais la
|                     pluie tombe dans la bouteille.
| But : franchir l'arrivée avec une bouteille remplie à moins de 10 %.
|
| Toutes les valeurs sont des entiers pour que le moteur PHP et le front-end
| JS donnent exactement le même résultat.
|
*/

return [

    // Durée d'un tick en millisecondes (50 ms = 20 ticks par seconde).
    'tick_ms' => 50,

    // Longueur de la course, en mètres.
    'distance' => 1200,

    // Sécurité : une partie ne peut pas dépasser ce nombre de ticks.
    // Doit être >= distance * 1000 / vitesse_min pour que l'arrivée soit toujours atteignable.
    'duree_max_ticks' => 2400,

    // Phases de pluie (dans le temps, en ticks).
    'phases' => [
        'duree_min' => 100,
        'duree_max' => 300,

        // "intensite" = quantité d'eau qui tombe par tick.
        'types' => [
            'bruine' => ['intensite' => 1],
            'averse' => ['intensite' => 2],
            'orage' => ['intensite' => 4],
        ],
    ],

    // Arbres le long du parcours (en mètres). Dessous, il ne pleut pas.
    'arbres' => [
        'premier_apres' => 60,  // aucun arbre avant ce mètre
        'ecart_min' => 80,      // écart entre la fin d'un arbre et le suivant
        'ecart_max' => 200,
        'longueur_min' => 20,
        'longueur_max' => 50,
    ],

    // Vitesse du personnage, en millimètres par tick.
    'vitesse' => [
        'max' => 1000,  // main sèche : 1 m par tick (20 m/s)
        'min' => 500,   // main trempée
    ],

    // Main : mouillure de 0 (sèche) à 100 (trempée).
    'main' => [
        'mouillure_max' => 100,
        'gain_par_intensite' => 1,  // mouillure gagnée par tick = intensité x gain (main sur la bouteille)
        'sechage_par_tick' => 2,    // mouillure perdue par tick quand la main est dans le K-Way
    ],

    // Bouteille.
    'bouteille' => [
        'capacite' => 1000,
        'seuil_victoire_pourcent' => 10,  // victoire si remplissage < 10 %
    ],

];
