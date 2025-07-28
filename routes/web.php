<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\FridgeController;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->name('home');



Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/food', [FoodController::class, 'index'])->name('food.index');

    Route::middleware(['auth', 'verified'])->prefix('auth')->name('auth.')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    });

    Route::get('/fridge', [FridgeController::class, 'index'])->name('fridge.index');
    Route::post('/fridge/addFood', [FridgeController::class, 'addFood'])->name('fridge.addFood');
    Route::put('/fridge/updateFood/{id}', [FridgeController::class, 'updateFood'])->name('fridge.updateFood');
    Route::delete('/fridge/deleteFood/{id}', [FridgeController::class, 'deleteFood'])->name('fridge.deleteFood');
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
