<?php

use App\Http\Controllers\Admin\ParticipationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

// Administration (regle.md, section 3): admin account created by AdminUserSeeder.
Route::middleware('auth.basic')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/participations', [ParticipationController::class, 'index'])->name('participations.index');
    Route::get('/participations/export', [ParticipationController::class, 'export'])->name('participations.export');
});
