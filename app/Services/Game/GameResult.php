<?php

namespace App\Services\Game;

/**
 * Result of a game replayed by the GameEngine (regle.md, section 5 "Résultats").
 */
final readonly class GameResult
{
    public function __construct(
        public int $durationTicks,
        public int $fillPercent,
        public bool $wheelAllowed,
    ) {}

    /**
     * Keys as in the `finish` response (regle.md, section 4.3).
     *
     * @return array{fill_percent: int, duration_ticks: int, wheel_allowed: bool}
     */
    public function toArray(): array
    {
        return [
            'fill_percent' => $this->fillPercent,
            'duration_ticks' => $this->durationTicks,
            'wheel_allowed' => $this->wheelAllowed,
        ];
    }
}
