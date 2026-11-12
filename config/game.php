<?php

/*
|--------------------------------------------------------------------------
| Game configuration — see regle.md, section 5
|--------------------------------------------------------------------------
|
| Never sent to the front: the API only returns `won` and `scenario`.
|
*/

return [
    // Chance de gagner une participation, en pourcentage (0 à 100).
    'win_probability' => 30,

    // Nombre de variantes d'animation pour chaque résultat.
    'scenarios' => [
        'won' => 3,
        'lost' => 3,
    ],

    // Lots du tirage final, dans l'ordre (rang 1 = premier lot).
    'prizes' => [
        1 => 'Une K-Way Le Vrai, un an de Quézac et un séjour en Lozère',
        2 => 'Une K-Way Le Vrai et 3 mois de Quézac',
        3 => 'Un accessoire K-Way et 1 mois de Quézac',
    ],
];
