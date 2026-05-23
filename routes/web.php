<?php

use App\Http\Controllers\BattleController;
use App\Http\Controllers\GearController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\MiniEventController;
use App\Http\Controllers\PartyRoomController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PotionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TalentSkillController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\WorldChatController;
use App\Models\TopUp;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/',[MapController::class,'index']);

Route::middleware('auth')->group(function () {
Route::prefix('world')->group(function () {
    Route::get('/map/{map_id}', [\App\Http\Controllers\WorldMapController::class, 'worldMap'])->name('world.map');
});
    Route::post('/battle/save',[BattleController::class,'saveBattle']);
    Route::post('/heal', [PlayerController::class,'healPlayer']);
    Route::get('/open-inventory', [InventoryController::class,'openInventory']);

    Route::prefix('streams')->group(function () {
        Route::get('/get-players', [\App\Http\Controllers\PlayerController::class, 'getPlayers']);
        Route::get('/get-world-chat', [\App\Http\Controllers\WorldChatController::class, 'getworldChat']);
    });

    Route::post('/send-message', [WorldChatController::class, 'sendMessage']);

    Route::get('/get-ranking',[PlayerController::class,'getPlayerRanking']);
    Route::get('/get-weapons',[GearController::class,'getWeapons']);
    Route::get('/get-armors',[GearController::class,'getArmors']);
    Route::get('/get-wings',[GearController::class,'getWings']);
    Route::post('/market-buy',[GearController::class,'marketBuy']);

    Route::get('/get-potions',[PotionController::class,'getPotions']);
    Route::post('/buy-potion',[PotionController::class,'buyPotion']);
    Route::post('/use-potion',[PotionController::class,'usePotion']);

    Route::get('/get-talents',[TalentSkillController::class,'getTalentSkills']);
    Route::post('/reset-talents',[TalentSkillController::class,'resetTalents']);
    Route::post('/store-selected-talents',[TalentSkillController::class,'storeSelectedTalents']);

    Route::post('/update-player-move',[PlayerController::class,'updatePlayerMove']);
    Route::get('/get-player',[PlayerController::class,'getPlayer']);
    Route::get('/get-crafting-materials',[GearController::class,'getCraftingMaterials']);

    Route::post('/craft-gear',[GearController::class,'craftGear']);
    Route::post('/upgrade-gear',[GearController::class,'upgradeGear']);
    Route::post('/use-gear',[InventoryController::class,'useGear']);

    Route::post('/party-room/create',[PartyRoomController::class,'createRoom']);
    Route::get('/get-party',[PartyRoomController::class,'getParty']);
    Route::post('/party-room/leave/{room_id}',[PartyRoomController::class,'leaveRoom']);
    Route::post('/party-room/join/{code}',[PartyRoomController::class,'joinRoom']);

    Route::post('/mini-event/bet',[MiniEventController::class,'miniEventBet']);
    Route::post('/mini-event/trivia',[MiniEventController::class,'miniEventTrivia']);

    Route::post('/submit-topup',[TopUpController::class,'submitTopUp']);
    Route::get('/get-top-ups', [TopUpController::class,'getTopUps']);
    Route::post('/approve-top-up',[TopUpController::class,'approveTopUp']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
