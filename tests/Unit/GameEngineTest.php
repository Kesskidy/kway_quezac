<?php

namespace Tests\Unit;

use App\Services\Game\GameEngine;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class GameEngineTest extends TestCase
{
    /**
     * Fixed rules (regle.md section 5 defaults), so tests do not depend on config/game.php.
     */
    private function engine(array $overrides = []): GameEngine
    {
        return new GameEngine([...[
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
        ], ...$overrides]);
    }

    private function dryCourse(array $trees = []): array
    {
        return ['phases' => [], 'trees' => $trees];
    }

    private function rainyCourse(array $trees = [], int $endTick = 4000): array
    {
        return [
            'phases' => [['type' => 'rain', 'start_tick' => 0, 'end_tick' => $endTick]],
            'trees' => $trees,
        ];
    }

    public function test_dry_course_runs_at_full_speed_without_filling(): void
    {
        $result = $this->engine(['course_length' => 30])->simulate($this->dryCourse(), []);

        $this->assertSame(10, $result->durationTicks);
        $this->assertSame(0, $result->fillPercent);
        $this->assertTrue($result->wheelAllowed);
    }

    public function test_uncovered_bottle_fills_in_the_rain(): void
    {
        $result = $this->engine(['course_length' => 300])->simulate($this->rainyCourse(), []);

        $this->assertSame(100, $result->durationTicks);
        $this->assertSame(20, $result->fillPercent); // 100 ticks x 2 = 200 / 1000
        $this->assertFalse($result->wheelAllowed);
    }

    public function test_covering_in_the_rain_keeps_the_bottle_empty_but_slows_down(): void
    {
        $result = $this->engine(['course_length' => 300])->simulate(
            $this->rainyCourse(),
            [['tick' => 0, 'action' => 'cover']],
        );

        $this->assertSame(150, $result->durationTicks); // 300 / speed_hand_wet (2)
        $this->assertSame(0, $result->fillPercent);
        $this->assertTrue($result->wheelAllowed);
    }

    public function test_covering_when_dry_does_not_slow_down(): void
    {
        $result = $this->engine(['course_length' => 30])->simulate(
            $this->dryCourse(),
            [['tick' => 0, 'action' => 'cover']],
        );

        $this->assertSame(10, $result->durationTicks);
    }

    public function test_tree_shelters_from_the_rain(): void
    {
        $result = $this->engine(['course_length' => 30])->simulate(
            $this->rainyCourse(trees: [['start' => 0, 'end' => 15]]),
            [],
        );

        // Positions 0, 3, 6, 9, 12 sheltered; 15 to 27 wet: 5 ticks x 2 = 10 → 1 %.
        $this->assertSame(10, $result->durationTicks);
        $this->assertSame(1, $result->fillPercent);
    }

    public function test_covering_under_a_tree_does_not_slow_down(): void
    {
        $result = $this->engine(['course_length' => 30])->simulate(
            $this->rainyCourse(trees: [['start' => 0, 'end' => 30]]),
            [['tick' => 0, 'action' => 'cover']],
        );

        $this->assertSame(10, $result->durationTicks);
    }

    public function test_it_is_dry_after_the_last_phase(): void
    {
        $result = $this->engine(['course_length' => 30, 'bottle_capacity' => 100])->simulate(
            $this->rainyCourse(endTick: 5),
            [],
        );

        $this->assertSame(10, $result->durationTicks);
        $this->assertSame(10, $result->fillPercent); // 5 rainy ticks x 2 = 10 / 100
    }

    public function test_dry_phases_do_not_fill(): void
    {
        $course = [
            'phases' => [
                ['type' => 'dry', 'start_tick' => 0, 'end_tick' => 5],
                ['type' => 'rain', 'start_tick' => 5, 'end_tick' => 4000],
            ],
            'trees' => [],
        ];

        $result = $this->engine(['course_length' => 30, 'bottle_capacity' => 100])->simulate($course, []);

        $this->assertSame(10, $result->fillPercent); // ticks 5 to 9 only
    }

    public function test_bottle_never_overflows(): void
    {
        $result = $this->engine(['course_length' => 3000])->simulate($this->rainyCourse(), []);

        $this->assertSame(1000, $result->durationTicks);
        $this->assertSame(100, $result->fillPercent);
    }

    public function test_wheel_is_allowed_up_to_9_percent_only(): void
    {
        // 49 ticks x 2 = 98 → 9 %.
        $nine = $this->engine(['course_length' => 147])->simulate($this->rainyCourse(), []);
        // 50 ticks x 2 = 100 → 10 %.
        $ten = $this->engine(['course_length' => 150])->simulate($this->rainyCourse(), []);

        $this->assertSame(9, $nine->fillPercent);
        $this->assertTrue($nine->wheelAllowed);
        $this->assertSame(10, $ten->fillPercent);
        $this->assertFalse($ten->wheelAllowed);
    }

    public function test_game_stops_at_max_ticks(): void
    {
        $result = $this->engine(['course_length' => 30, 'max_ticks' => 5])->simulate($this->dryCourse(), []);

        $this->assertSame(5, $result->durationTicks);
    }

    public function test_input_is_applied_at_its_own_tick(): void
    {
        $result = $this->engine(['course_length' => 30, 'bottle_capacity' => 100])->simulate(
            $this->rainyCourse(),
            [
                ['tick' => 2, 'action' => 'cover'],
                ['tick' => 6, 'action' => 'uncover'],
            ],
        );

        // Ticks 0-1 uncovered (+3, fill 4), 2-5 covered (+2, pos 14), then uncovered: 16 left → 6 ticks (fill +12).
        $this->assertSame(12, $result->durationTicks);
        $this->assertSame(16, $result->fillPercent);
    }

    public function test_replaying_the_same_inputs_gives_the_same_result(): void
    {
        $inputs = [
            ['tick' => 3, 'action' => 'cover'],
            ['tick' => 40, 'action' => 'uncover'],
            ['tick' => 55, 'action' => 'cover'],
        ];

        $this->assertEquals(
            $this->engine()->simulate($this->rainyCourse(), $inputs),
            $this->engine()->simulate($this->rainyCourse(), $inputs),
        );
    }

    public function test_result_uses_contract_keys(): void
    {
        $result = $this->engine(['course_length' => 30])->simulate($this->dryCourse(), []);

        $this->assertSame(
            ['fill_percent' => 0, 'duration_ticks' => 10, 'wheel_allowed' => true],
            $result->toArray(),
        );
    }

    public static function invalidInputs(): array
    {
        return [
            'starts with uncover' => [[['tick' => 0, 'action' => 'uncover']]],
            'two covers in a row' => [[['tick' => 0, 'action' => 'cover'], ['tick' => 5, 'action' => 'cover']]],
            'ticks not strictly increasing' => [[['tick' => 5, 'action' => 'cover'], ['tick' => 5, 'action' => 'uncover']]],
            'negative tick' => [[['tick' => -1, 'action' => 'cover']]],
            'tick equal to max_ticks' => [[['tick' => 4000, 'action' => 'cover']]],
            'non integer tick' => [[['tick' => '3', 'action' => 'cover']]],
            'unknown action' => [[['tick' => 0, 'action' => 'jump']]],
            'missing tick' => [[['action' => 'cover']]],
        ];
    }

    #[DataProvider('invalidInputs')]
    public function test_invalid_inputs_are_rejected(array $inputs): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->engine()->simulate($this->dryCourse(), $inputs);
    }

    public function test_more_than_max_inputs_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->engine(['max_inputs' => 2])->simulate($this->dryCourse(), [
            ['tick' => 0, 'action' => 'cover'],
            ['tick' => 1, 'action' => 'uncover'],
            ['tick' => 2, 'action' => 'cover'],
        ]);
    }
}
