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

    public function getPlayerRanking()
    {
        $players = Player::query()
            ->select('name','current_level','class_type')
            ->orderByDesc('current_level')
            ->orderByDesc('current_experience')
            ->limit(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'rankings' => $players
        ]);
    }
}
