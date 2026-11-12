<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\GameSession
 */
class GameSessionResource extends JsonResource
{
    /**
     * Response of POST /api/game-sessions (regle.md, section 4.1).
     *
     * @return array{token: string, won: bool, scenario: int}
     */
    public function toArray(Request $request): array
    {
        return [
            'token' => $this->token,
            'won' => $this->won,
            'scenario' => $this->scenario,
        ];
    }
}
