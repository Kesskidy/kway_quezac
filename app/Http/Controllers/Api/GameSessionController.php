<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StartGameRequest;
use App\Http\Resources\GameSessionResource;
use App\Models\GameSession;
use App\Models\PromoCode;
use App\Services\Game\CourseGenerator;
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
