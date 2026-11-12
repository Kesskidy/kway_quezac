<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinishGameRequest;
use App\Http\Requests\StartGameRequest;
use App\Http\Resources\GameSessionResource;
use App\Models\GameSession;
use App\Models\PromoCode;
use App\Services\Game\CourseGenerator;
use App\Services\Game\GameEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class GameSessionController extends Controller
{
    /**
     * Rules sent to the front in `rules` (regle.md, section 4.2).
     */
    private const PUBLIC_RULES = [
        'ticks_per_second',
        'course_length',
        'speed',
        'speed_hand_wet',
        'bottle_capacity',
        'rain_per_tick',
        'max_fill_percent_for_wheel',
        'max_ticks',
    ];

    /**
     * POST /api/game-sessions — consumes the code and creates the game (regle.md, section 4.1).
     */
    public function store(StartGameRequest $request, CourseGenerator $courseGenerator): JsonResponse
    {
        $gameSession = DB::transaction(function () use ($request, $courseGenerator) {
            $promoCode = PromoCode::where('code', $request->validated('code'))
                ->lockForUpdate()
                ->firstOrFail();

            if ($promoCode->isUsed()) {
                return null;
            }

            $promoCode->update(['used_at' => now()]);

            return $promoCode->gameSession()->create([
                'course' => $courseGenerator->generate(),
            ]);
        });

        if ($gameSession === null) {
            return $this->error('code', 'Ce code a déjà été utilisé.', 409);
        }

        return (new GameSessionResource($gameSession))->response()->setStatusCode(201);
    }

    /**
     * GET /api/game-sessions/{token}/course — records started_at on the first call (regle.md, section 4.2).
     */
    public function course(GameSession $gameSession): JsonResponse
    {
        if ($gameSession->isFinished()) {
            return $this->error('game_session', 'Cette partie est déjà terminée.', 409);
        }

        $gameSession->markStarted();

        return response()->json([
            'data' => [
                'phases' => $gameSession->course['phases'],
                'trees' => $gameSession->course['trees'],
                'rules' => Arr::only(config('game'), self::PUBLIC_RULES),
            ],
        ]);
    }

    /**
     * POST /api/game-sessions/{token}/finish — replays the inputs on the server and saves the result
     * (regle.md, sections 4.3 and 6). The front only sends inputs, never a score.
     */
    public function finish(FinishGameRequest $request, GameSession $gameSession, GameEngine $engine): JsonResponse
    {
        return DB::transaction(function () use ($request, $gameSession, $engine) {
            // Lock the row so two simultaneous `finish` calls cannot both save a result.
            $gameSession = GameSession::whereKey($gameSession->getKey())->lockForUpdate()->firstOrFail();

            if ($gameSession->isFinished()) {
                return $this->error('game_session', 'Cette partie est déjà terminée.', 409);
            }

            if ($gameSession->started_at === null) {
                return $this->error('inputs', 'Cette partie n’a pas encore commencé.', 422);
            }

            $inputs = $request->inputs();
            $result = $engine->simulate($gameSession->course, $inputs);

            $elapsedSeconds = $gameSession->started_at->diffInMilliseconds(now()) / 1000;
            $minimumSeconds = $result->durationTicks / config('game.ticks_per_second') * config('game.real_time_tolerance');

            if ($elapsedSeconds < $minimumSeconds) {
                return $this->error('inputs', 'Partie terminée trop vite.', 422);
            }

            $gameSession->update([
                'finished_at' => now(),
                'inputs' => $inputs,
                'fill_percent' => $result->fillPercent,
                'duration_ticks' => $result->durationTicks,
                'wheel_allowed' => $result->wheelAllowed,
            ]);

            return response()->json(['data' => $result->toArray()]);
        });
    }

    /**
     * Error in the standard Laravel format (regle.md, section 4.5).
     */
    private function error(string $field, string $message, int $status): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => [$field => [$message]],
        ], $status);
    }
}
