<?php

use App\Http\Controllers\Admin\ParticipationController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

Route::view('/reglement', 'legal.rules')->name('legal.rules');
Route::view('/mentions-legales', 'legal.notice')->name('legal.notice');
Route::view('/confidentialite', 'legal.privacy')->name('legal.privacy');

Route::middleware('auth.basic')->group(function () {
    Route::get('/admin/participations', [ParticipationController::class, 'index'])->name('admin.participations.index');
    Route::get('/admin/participations/export', [ParticipationController::class, 'export'])->name('admin.participations.export');
    Route::get('/admin/qr-codes', [QrCodeController::class, 'index'])->name('admin.qr-codes.index');
});
