<?php

namespace App\Services\Game;

use InvalidArgumentException;

/**
 * Replays a game tick by tick — the server is the authority (regle.md, section 5).
 *
 * Start: tick = 0, position = 0, fill = 0, covered = false. Each tick, in this order:
 *  1. apply the input whose tick equals the current tick (cover → covered, uncover → not covered);
 *  2. raining  = a `rain` phase has start_tick <= tick < end_tick (dry after the last phase);
 *  3. sheltered = a tree has start <= position < end;
 *  4. wet = raining && !sheltered;
 *  5. speed = speed_hand_wet if wet && covered, otherwise speed;
 *  6. if wet && !covered: fill = min(fill + rain_per_tick, bottle_capacity);
 *  7. position += speed;
 *  8. tick += 1.
 * Stops when position >= course_length or tick >= max_ticks.
 */
class GameEngine
{
    public const COVER = 'cover';

    public const UNCOVER = 'uncover';

    /** @var array<string, mixed> */
    private array $rules;

    /**
     * @param  array<string, mixed>|null  $rules  Defaults to config('game').
     */
    public function __construct(?array $rules = null)
    {
        $this->rules = $rules ?? config('game');
    }

    /**
     * @param  array{phases: list<array{type: string, start_tick: int, end_tick: int}>, trees: list<array{start: int, end: int}>}  $course
     * @param  list<array{tick: int, action: string}>  $inputs
     *
     * @throws InvalidArgumentException when the inputs break the rules of regle.md, section 6.
     */
    public function simulate(array $course, array $inputs): GameResult
    {
        $actionsByTick = $this->indexInputs($inputs);
        $rules = $this->rules;

        $tick = 0;
        $position = 0;
        $fill = 0;
        $covered = false;

        while ($position < $rules['course_length'] && $tick < $rules['max_ticks']) {
            if (isset($actionsByTick[$tick])) {
                $covered = $actionsByTick[$tick] === self::COVER;
            }

            $wet = $this->isRaining($course['phases'], $tick)
                && ! $this->isSheltered($course['trees'], $position);

            $speed = $wet && $covered ? $rules['speed_hand_wet'] : $rules['speed'];

            if ($wet && ! $covered) {
                $fill = min($fill + $rules['rain_per_tick'], $rules['bottle_capacity']);
            }

            $position += $speed;
            $tick++;
        }

        $fillPercent = intdiv($fill * 100, $rules['bottle_capacity']);

        return new GameResult(
            durationTicks: $tick,
            fillPercent: $fillPercent,
            wheelAllowed: $fillPercent <= $rules['max_fill_percent_for_wheel'],
        );
    }

    /**
     * @param  list<array{type: string, start_tick: int, end_tick: int}>  $phases
     */
    private function isRaining(array $phases, int $tick): bool
    {
        foreach ($phases as $phase) {
            if ($phase['type'] === 'rain' && $phase['start_tick'] <= $tick && $tick < $phase['end_tick']) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<array{start: int, end: int}>  $trees
     */
    private function isSheltered(array $trees, int $position): bool
    {
        foreach ($trees as $tree) {
            if ($tree['start'] <= $position && $position < $tree['end']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks the inputs (regle.md, section 6) and indexes their action by tick:
     * at most max_inputs, integer ticks with 0 <= tick < max_ticks, strictly increasing,
     * actions alternating and starting with `cover`.
     *
     * @param  list<array{tick: int, action: string}>  $inputs
     * @return array<int, string>
     */
    private function indexInputs(array $inputs): array
    {
        if (count($inputs) > $this->rules['max_inputs']) {
            throw new InvalidArgumentException('Too many inputs.');
        }

        $actionsByTick = [];
        $previousTick = -1;
        $expectedAction = self::COVER;

        foreach (array_values($inputs) as $index => $input) {
            $tick = $input['tick'] ?? null;
            $action = $input['action'] ?? null;

            if (! is_int($tick) || $tick < 0 || $tick >= $this->rules['max_ticks']) {
                throw new InvalidArgumentException("Input {$index}: invalid tick.");
            }
            if ($tick <= $previousTick) {
                throw new InvalidArgumentException("Input {$index}: ticks must be strictly increasing.");
            }
            if ($action !== $expectedAction) {
                throw new InvalidArgumentException("Input {$index}: `{$expectedAction}` expected.");
            }

            $actionsByTick[$tick] = $action;
            $previousTick = $tick;
            $expectedAction = $action === self::COVER ? self::UNCOVER : self::COVER;
        }

        return $actionsByTick;
    }
}
