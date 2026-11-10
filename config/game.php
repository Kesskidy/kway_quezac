<?php

/*
|--------------------------------------------------------------------------
| Game rules — see regle.md, section 5
|--------------------------------------------------------------------------
|
| Rule values live only here. The server sends them to the front in
| `rules` (GET /api/game-sessions/{token}/course); the JS hard-codes none.
| All values are integers so PHP and JS simulate identically.
|
*/

return [
    'ticks_per_second' => 20,
    'course_length' => 6000,
    'speed' => 3,
    'speed_hand_wet' => 2,
    'bottle_capacity' => 1000,
    'rain_per_tick' => 2,
    'max_fill_percent_for_wheel' => 9,
    'max_ticks' => 4000,
    'max_inputs' => 600,
    'real_time_tolerance' => 0.9,

    /*
    | Course generation (CourseGenerator). Not sent to the front:
    | the front receives the generated phases and trees.
    | Each value is [min, max].
    */
    'generation' => [
        'dry_phase_ticks' => [120, 260],   // ticks, phase `dry`
        'rain_phase_ticks' => [150, 320],  // ticks, phase `rain`
        'first_tree_after' => 400,         // position, no tree before
        'tree_gap' => [700, 1500],         // position, gap between two trees
        'tree_length' => [120, 200],       // position
    ],
];
