<?php

use App\Http\Controllers\ParcoursController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::get('/parcours', ParcoursController::class)->name('parcours');
