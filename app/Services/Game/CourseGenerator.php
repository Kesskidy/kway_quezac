<?php

namespace App\Services\Game;

use Random\Engine\Mt19937;
use Random\Randomizer;

/**
 * Generates the course of a game (regle.md, section 4.2):
 * - `phases`: alternate `dry` / `rain`, starting with `dry`, contiguous up to max_ticks (in ticks);
 * - `trees`: non-overlapping shelters along the course, before course_length (in positions).
 */
class CourseGenerator
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
     * @param  int|null  $seed  Same seed = same course (useful for tests).
     * @return array{phases: list<array{type: string, start_tick: int, end_tick: int}>, trees: list<array{start: int, end: int}>}
     */
    public function generate(?int $seed = null): array
    {
        $random = new Randomizer($seed === null ? null : new Mt19937($seed));

        return [
            'phases' => $this->generatePhases($random),
            'trees' => $this->generateTrees($random),
        ];
    }

    /**
     * @return list<array{type: string, start_tick: int, end_tick: int}>
     */
    private function generatePhases(Randomizer $random): array
    {
        $maxTicks = $this->config['max_ticks'];
        $generation = $this->config['generation'];
        $phases = [];
        $tick = 0;
        $type = 'dry';

        while ($tick < $maxTicks) {
            [$min, $max] = $generation[$type === 'dry' ? 'dry_phase_ticks' : 'rain_phase_ticks'];
            $endTick = min($maxTicks, $tick + $random->getInt($min, $max));

            $phases[] = ['type' => $type, 'start_tick' => $tick, 'end_tick' => $endTick];

            $tick = $endTick;
            $type = $type === 'dry' ? 'rain' : 'dry';
        }

        return $phases;
    }

    /**
     * @return list<array{start: int, end: int}>
     */
    private function generateTrees(Randomizer $random): array
    {
        $courseLength = $this->config['course_length'];
        $generation = $this->config['generation'];
        $trees = [];
        $position = $generation['first_tree_after'];

        while (true) {
            $start = $position + $random->getInt(...$generation['tree_gap']);
            $end = $start + $random->getInt(...$generation['tree_length']);

            if ($end >= $courseLength) {
                break;
            }

            $trees[] = ['start' => $start, 'end' => $end];
            $position = $end;
        }

        return $trees;
    }
}
