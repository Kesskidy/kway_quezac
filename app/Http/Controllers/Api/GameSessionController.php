<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StartGameRequest;
use App\Http\Requests\StoreParticipationRequest;
use App\Http\Resources\GameSessionResource;
use App\Models\GameSession;
use App\Models\PromoCode;
use App\Services\Game\GameDraw;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GameSessionController extends Controller
{
    /**
     * POST /api/game-sessions — consumes the code and draws the result in the same
     * transaction: 1 code = 1 game = 1 draw (regle.md, sections 4.1 and 5).
     */
    public function store(StartGameRequest $request, GameDraw $gameDraw): JsonResponse
    {
        $gameSession = DB::transaction(function () use ($request, $gameDraw) {
            $promoCode = PromoCode::where('code', $request->validated('code'))
                ->lockForUpdate()
                ->first();

            if ($promoCode === null) {
                throw ValidationException::withMessages(['code' => 'Ce code est invalide.']);
            }

            if ($promoCode->isUsed()) {
                throw new HttpResponseException($this->error('code', 'Ce code a déjà été utilisé.', 409));
            }

            $promoCode->update(['used_at' => now()]);

            return $promoCode->gameSession()->create($gameDraw->draw());
        });

        return (new GameSessionResource($gameSession))->response()->setStatusCode(201);
    }

    /**
     * POST /api/game-sessions/{token}/participation — registers the winner for the final
     * prize draw (regle.md, section 4.2). A lost game is refused by the request (403).
     */
    public function participation(StoreParticipationRequest $request, GameSession $gameSession): JsonResponse
    {
        try {
            DB::transaction(function () use ($request, $gameSession) {
                // Lock the game so two simultaneous calls cannot both register a participation.
                $gameSession = GameSession::whereKey($gameSession->getKey())->lockForUpdate()->firstOrFail();

                if ($gameSession->participation()->exists()) {
                    throw new HttpResponseException($this->participationAlreadyRegistered());
                }

                $gameSession->participation()->create([
                    'first_name' => $request->validated('first_name'),
                    'email' => $request->validated('email'),
                    'consented_at' => now(),
                ]);
            });
        } catch (UniqueConstraintViolationException) {
            // Safety net: game_session_id is unique in the database.
            return $this->participationAlreadyRegistered();
        }

        return response()->json([
            'data' => ['message' => "C'est noté ! Ta participation au tirage des lots est enregistrée."],
        ], 201);
    }

    private function participationAlreadyRegistered(): JsonResponse
    {
        return $this->error('game_session', 'Ta participation est déjà enregistrée.', 409);
    }

    /**
     * Error in the standard Laravel format (regle.md, section 4.3).
     */
    private function error(string $field, string $message, int $status): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => [$field => [$message]],
        ], $status);
    }
}
