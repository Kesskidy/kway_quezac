<?php

use App\Http\Controllers\Admin\ParticipationController;
use App\Http\Controllers\ExpeditionRegistrationController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\NewsletterSubscriberController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::post('/newsletter', [NewsletterSubscriberController::class, 'store'])->middleware('throttle:10,1')->name('newsletter.store');
Route::post('/expedition', [ExpeditionRegistrationController::class, 'store'])->middleware('throttle:10,1')->name('expedition.store');

Route::view('/reglement', 'legal.rules')->name('legal.rules');
Route::view('/mentions-legales', 'legal.notice')->name('legal.notice');
Route::view('/confidentialite', 'legal.privacy')->name('legal.privacy');

Route::middleware('auth.basic')->group(function () {
    Route::get('/admin/participations', [ParticipationController::class, 'index'])->name('admin.participations.index');
    Route::get('/admin/participations/export', [ParticipationController::class, 'export'])->name('admin.participations.export');
    Route::get('/admin/newsletter/export', [ParticipationController::class, 'exportNewsletter'])->name('admin.newsletter.export');
    Route::get('/admin/expedition/export', [ParticipationController::class, 'exportExpedition'])->name('admin.expedition.export');
});
