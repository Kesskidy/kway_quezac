<?php

namespace Tests\Unit;

use App\Services\Game\CourseGenerator;
use PHPUnit\Framework\TestCase;

class CourseGeneratorTest extends TestCase
{
    private function generator(): CourseGenerator
    {
        return new CourseGenerator([
            'course_length' => 6000,
            'max_ticks' => 4000,
            'generation' => [
                'dry_phase_ticks' => [120, 260],
                'rain_phase_ticks' => [150, 320],
                'first_tree_after' => 400,
                'tree_gap' => [700, 1500],
                'tree_length' => [120, 200],
            ],
        ]);
    }

    public function test_phases_alternate_from_dry_and_cover_the_whole_game(): void
    {
        foreach ([1, 42, 777] as $seed) {
            $phases = $this->generator()->generate($seed)['phases'];

            $this->assertSame(0, $phases[0]['start_tick']);
            $this->assertSame(4000, end($phases)['end_tick']);

            foreach ($phases as $i => $phase) {
                $this->assertSame(['type', 'start_tick', 'end_tick'], array_keys($phase));
                $this->assertSame($i % 2 === 0 ? 'dry' : 'rain', $phase['type']);
                $this->assertGreaterThan($phase['start_tick'], $phase['end_tick']);

                if ($i > 0) {
                    $this->assertSame($phases[$i - 1]['end_tick'], $phase['start_tick']);
                }
            }
        }
    }

    public function test_trees_do_not_overlap_and_stay_on_the_course(): void
    {
        foreach ([1, 42, 777] as $seed) {
            $trees = $this->generator()->generate($seed)['trees'];

            $this->assertNotEmpty($trees);
            $previousEnd = 400;

            foreach ($trees as $tree) {
                $this->assertSame(['start', 'end'], array_keys($tree));
                $this->assertGreaterThan($previousEnd, $tree['start']);
                $this->assertGreaterThan($tree['start'], $tree['end']);
                $this->assertLessThan(6000, $tree['end']);
                $previousEnd = $tree['end'];
            }
        }
    }

    public function test_same_seed_gives_the_same_course(): void
    {
        $this->assertSame($this->generator()->generate(42), $this->generator()->generate(42));
        $this->assertNotSame($this->generator()->generate(1), $this->generator()->generate(2));
    }
}
