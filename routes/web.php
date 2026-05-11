<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::prefix('world')->group(function () {
    Route::get('/map/{map_id}', [\App\Http\Controllers\WorldMapController::class, 'worldMap'])->name('world.map');
});

Route::prefix('streams')->group(function () {
    Route::get('/get-players', [\App\Http\Controllers\PlayerController::class, 'index'])->name('stream.players');
    Route::get('/get-world-chat', [\App\Http\Controllers\WorldChatController::class, 'worldChat'])->name('stream.world-chat');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
