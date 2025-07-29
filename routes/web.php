<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FoodController;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->name('home');



Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/food', [FoodController::class, 'index'])->name('food.index');

    Route::middleware(['auth', 'verified'])->prefix('auth')->name('auth.')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    });

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
