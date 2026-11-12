<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StartGameRequest;
use App\Http\Resources\GameSessionResource;
use App\Models\PromoCode;
use App\Services\Game\GameDraw;
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
