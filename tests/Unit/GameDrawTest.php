<?php

namespace Tests\Unit;

use App\Services\Game\GameDraw;
use Tests\TestCase;

class GameDrawTest extends TestCase
{
    public function test_probability_0_always_loses_and_100_always_wins(): void
    {
        config(['game.win_probability' => 0]);
        $this->assertFalse((new GameDraw)->draw()['won']);

        config(['game.win_probability' => 100]);
        $this->assertTrue((new GameDraw)->draw()['won']);
    }

    public function test_scenario_stays_between_0_and_2(): void
    {
        foreach (range(1, 100) as $i) {
            $this->assertContains((new GameDraw)->draw()['scenario'], [0, 1, 2]);
        }
    }
}
