<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function healPlayer()
    {
        $player = auth()->user()->player;

        $player->current_health = $player->max_health;
        $player->current_mana = $player->max_mana;
        $player->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Got healed.',
            'player' => PlayerResource::make($player),
        ]);
    }
}
