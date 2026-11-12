<?php

/*
|--------------------------------------------------------------------------
| Legal pages (regle.md, section 10)
|--------------------------------------------------------------------------
|
| Shown on /reglement, /mentions-legales and /confidentialite.
| Fictitious student project: placeholder values, to adjust if needed.
|
*/

return [
    // Game period and final prize draw (dates shown in the rules).
    'game_starts_on' => '2027-03-01',
    'game_ends_on' => '2027-05-31',
    'final_draw_on' => '2027-06-15',

    // Personal data: contact for privacy requests, and how long participations are kept.
    'contact_email' => 'contact@example.com',
    'data_retention_months' => 6,

    // Authors of the project (publisher in the legal notice).
    'authors' => 'Mathéo et Clément, étudiants',
];
