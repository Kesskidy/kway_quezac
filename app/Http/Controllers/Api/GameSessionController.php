<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StartGameRequest;
use App\Http\Requests\StoreParticipationRequest;
use App\Http\Resources\GameSessionResource;
use App\Mail\ParticipationConfirmed;
use App\Models\GameSession;
use App\Models\PromoCode;
use App\Services\Game\GameDraw;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class GameSessionController extends Controller
{
    public function store(StartGameRequest $request, GameDraw $gameDraw): GameSessionResource
    {
        $gameSession = DB::transaction(function () use ($request, $gameDraw) {
            // The lock makes two simultaneous requests with the same code wait for each other,
            // so a code can never be played twice (1 code = 1 draw).
            $promoCode = PromoCode::where('code', $request->validated('code'))->lockForUpdate()->first();

            if (! $promoCode) {
                throw ValidationException::withMessages(['code' => 'Ce code est invalide.']);
            }

            if ($promoCode->used_at) {
                throw ValidationException::withMessages(['code' => 'Ce code a déjà été utilisé.'])->status(409);
            }

            $promoCode->update(['used_at' => now()]);

            return $promoCode->gameSession()->create($gameDraw->draw());
        });

        return new GameSessionResource($gameSession);
    }

    public function participation(StoreParticipationRequest $request, GameSession $gameSession): JsonResponse
    {
        if ($gameSession->participation()->exists()) {
            throw ValidationException::withMessages(['game_session' => 'Ta participation est déjà enregistrée.'])->status(409);
        }

        $participation = $gameSession->participation()->create([
            ...$request->safe()->only(['first_name', 'email']),
            'consented_at' => now(),
        ]);

        Mail::to($participation->email)->send(new ParticipationConfirmed($participation));

        return response()->json([
            'data' => ['message' => "C'est noté ! Ta participation au tirage des lots est enregistrée."],
        ], 201);
    }
}
