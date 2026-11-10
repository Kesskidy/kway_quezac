<?php

use App\Http\Controllers\ParcoursController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/parcours', ParcoursController::class)->name('parcours');
