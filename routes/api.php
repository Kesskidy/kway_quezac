<?php

use App\Http\Controllers\Api\GameSessionController;
use Illuminate\Support\Facades\Route;

// Game API (regle.md, section 3). The /api prefix is added automatically.

Route::post('/game-sessions', [GameSessionController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('game-sessions.store');

Route::post('/game-sessions/{gameSession:token}/participation', [GameSessionController::class, 'participation'])
    ->middleware('throttle:10,1')
    ->name('game-sessions.participation');
