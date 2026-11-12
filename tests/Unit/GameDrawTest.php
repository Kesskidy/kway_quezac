<?php

namespace Tests\Unit;

use App\Services\Game\GameDraw;
use PHPUnit\Framework\TestCase;

class GameDrawTest extends TestCase
{
    private function draws(int $probability, int $count = 500): array
    {
        $gameDraw = new GameDraw([
            'win_probability' => $probability,
            'scenarios' => ['won' => 3, 'lost' => 2],
        ]);

        return array_map(fn () => $gameDraw->draw(), range(1, $count));
    }

    public function test_probability_0_never_wins(): void
    {
        foreach ($this->draws(0) as $draw) {
            $this->assertFalse($draw['won']);
        }
    }

    public function test_probability_100_always_wins(): void
    {
        foreach ($this->draws(100) as $draw) {
            $this->assertTrue($draw['won']);
        }
    }

    public function test_scenario_stays_in_the_range_of_its_outcome(): void
    {
        $seen = ['won' => [], 'lost' => []];

        foreach ($this->draws(50, 2000) as $draw) {
            $outcome = $draw['won'] ? 'won' : 'lost';
            $seen[$outcome][$draw['scenario']] = true;
        }

        // won: 0 to 2, lost: 0 to 1, and every variant comes up.
        $this->assertEqualsCanonicalizing([0, 1, 2], array_keys($seen['won']));
        $this->assertEqualsCanonicalizing([0, 1], array_keys($seen['lost']));
    }

    public function test_draw_returns_won_and_scenario_only(): void
    {
        $draw = $this->draws(30, 1)[0];

        $this->assertSame(['won', 'scenario'], array_keys($draw));
        $this->assertIsBool($draw['won']);
        $this->assertIsInt($draw['scenario']);
    }
}
