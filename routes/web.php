<?php

use App\Http\Controllers\BattleController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorldChatController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::middleware('auth')->group(function () {
Route::prefix('world')->group(function () {
    Route::get('/map/{map_id}', [\App\Http\Controllers\WorldMapController::class, 'worldMap'])->name('world.map');
});

Route::prefix('npc')->name('npc.')->group(function () {
    Route::post('/', [WorldChatController::class, 'sendMessage'])->name('send-message');
});

Route::post('/battle/save',[BattleController::class,'saveBattle']);
Route::post('/heal', [PlayerController::class,'healPlayer']);
Route::get('/open-inventory', [InventoryController::class,'openInventory']);

Route::prefix('streams')->group(function () {
    Route::get('/get-players', [\App\Http\Controllers\PlayerController::class, 'getPlayers'])->name('stream.players');
    Route::get('/get-world-chat', [\App\Http\Controllers\WorldChatController::class, 'worldChat'])->name('stream.world-chat');
});

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
