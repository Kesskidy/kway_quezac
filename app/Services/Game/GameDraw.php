<?php

namespace App\Services\Game;

/**
 * Draws the result of a game when the code is entered (regle.md, section 5 "Le tirage").
 * Called inside the transaction that consumes the code: 1 code = 1 draw.
 */
class GameDraw
{
    /** @var array<string, mixed> */
    private array $config;

    /**
     * @param  array<string, mixed>|null  $config  Defaults to config('game').
     */
    public function __construct(?array $config = null)
    {
        $this->config = $config ?? config('game');
    }

    /**
     * @return array{won: bool, scenario: int}
     */
    public function draw(): array
    {
        $won = random_int(1, 100) <= $this->config['win_probability'];
        $scenario = random_int(0, $this->config['scenarios'][$won ? 'won' : 'lost'] - 1);

        return ['won' => $won, 'scenario' => $scenario];
    }
}
