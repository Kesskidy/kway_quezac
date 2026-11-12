<?php

use App\Http\Controllers\Api\GameSessionController;
use Illuminate\Support\Facades\Route;

// Game API (regle.md, section 3). The /api prefix is added automatically.

Route::post('/game-sessions', [GameSessionController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('game-sessions.store');

Route::get('/game-sessions/{gameSession:token}/course', [GameSessionController::class, 'course'])
    ->middleware('throttle:30,1')
    ->name('game-sessions.course');

Route::post('/game-sessions/{gameSession:token}/finish', [GameSessionController::class, 'finish'])
    ->middleware('throttle:10,1')
    ->name('game-sessions.finish');
