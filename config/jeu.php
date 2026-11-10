<?php

/*
|--------------------------------------------------------------------------
| Paramètres du jeu
|--------------------------------------------------------------------------
|
| Valeurs partagées par le générateur de parcours (/parcours), le moteur
| PHP (GameEngine) et le rendu front-end. Toutes les durées sont en ticks.
|
*/

return [

    // Durée d'un tick en millisecondes (50 ms = 20 ticks par seconde).
    'tick_ms' => 50,

    // Durée totale d'une partie (1200 ticks = 60 secondes).
    'duree_ticks' => 1200,

    // Phases de pluie qui se succèdent pendant la partie.
    'phases' => [
        'duree_min' => 100,
        'duree_max' => 300,

        // Types de pluie : "intensite" = quantité d'eau reçue par tick.
        'types' => [
            'bruine' => ['intensite' => 1],
            'averse' => ['intensite' => 2],
            'orage' => ['intensite' => 4],
        ],
    ],

    // Arbres croisés pendant le parcours (sous un arbre, pas de pluie).
    'arbres' => [
        'premier_apres' => 60,  // aucun arbre avant ce tick
        'ecart_min' => 80,      // écart entre la fin d'un arbre et le suivant
        'ecart_max' => 200,
        'duree_min' => 20,      // temps passé sous un arbre
        'duree_max' => 50,
    ],

];
