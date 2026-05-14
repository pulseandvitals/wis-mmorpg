<?php

use App\Http\Controllers\BattleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth')->group(function() {
    Route::post('/battle/save',[BattleController::class,'saveBattle']);
});
