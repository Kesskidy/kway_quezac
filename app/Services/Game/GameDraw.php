<?php

namespace App\Services\Game;

class GameDraw
{
    public function draw(): array
    {
        $won = random_int(1, 100) <= config('game.win_probability');
        $scenario = random_int(0, config('game.scenarios.'.($won ? 'won' : 'lost')) - 1);

        return ['won' => $won, 'scenario' => $scenario];
    }
}
