<?php

namespace App\Http\Controllers;

use App\Events\PlayerMoved;
use App\Events\ZoneStateUpdated;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{


    public function healPlayer(Request $request)
    {
        $player = auth()->user()->player;

        $player->current_health = $request->input('max_hp');
        $player->current_mana = $request->input('max_mp');
        $player->save();

        broadcast(new ZoneStateUpdated($player->current_map_id,[
            'id' => $player->id,
            'current_health' => $player->current_health
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'Got healed.',
            'player' => PlayerResource::make($player),
        ]);
    }

    public function sendEmoji(Request $request)
    {
        $player = auth()->user()->player;

        broadcast(new ZoneStateUpdated($player->current_map_id,[
                'id' => $player->id,
                'type' => 'player.update',
                'emoji' => $request->emoji['image'],
        ]));

        return response()->json([
            'emoji' => $request->emoji['image']
        ]);
    }

    public function getPlayerRanking()
    {
        $players = Player::query()
            ->select('name', 'current_level', 'class_type')
            ->whereHas('user', function ($q) {
                $q->where('is_admin', false);
            })
            ->orderByDesc('current_level')
            ->orderByDesc('current_experience')
            ->limit(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'rankings' => $players
        ]);
    }

    public function updatePlayerMove(Request $request)
    {
        $player = auth()->user()->player;
        $player->x = $request->x;
        $player->y = $request->y;
        $player->direction = $request->dir;
        $player->save();

        broadcast(new PlayerMoved($player))->toOthers();
    }

    public function getPlayers()
    {
        $players = Player::select([
            'id',
            'name',
            'x',
            'y',
            'direction',
            'class_type',
            'current_level',
            'wing_id',
            'in_pvp',
            'current_map_id',
            'max_health',
            'current_health',
            'max_mana',
            'current_mana',
            'guild_id'
        ])
        ->with(['wing.gear','guild'])
        ->where('id', '!=', auth()->user()->player->id)
        ->where('current_map_id','=',auth()->user()->player->current_map_id)
        ->where('is_online', true)
        ->get();

        return response()->json(PlayerResource::collection($players));
    }

    public function getActivePlayer()
    {

    }
}
