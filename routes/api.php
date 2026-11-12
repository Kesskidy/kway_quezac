<?php

use App\Http\Controllers\Api\GameSessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:10,1')->group(function () {
    Route::post('/game-sessions', [GameSessionController::class, 'store'])->name('game-sessions.store');
    Route::post('/game-sessions/{gameSession:token}/participation', [GameSessionController::class, 'participation'])->name('game-sessions.participation');
});
