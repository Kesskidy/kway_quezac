<?php

use App\Http\Controllers\Admin\ParticipationController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Pages (regle.md, section 3).
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

// Administration (regle.md, section 3): admin account created by AdminUserSeeder.
Route::middleware('auth.basic')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/participations', [ParticipationController::class, 'index'])->name('participations.index');
    Route::get('/participations/export', [ParticipationController::class, 'export'])->name('participations.export');
});
