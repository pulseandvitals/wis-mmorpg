<?php

namespace App\Http\Controllers;

use App\Models\Guild;
use App\Models\GuildMember;
use Illuminate\Http\Request;

class GuildController extends Controller
{
    public function createGuild(Request $request)
    {
        $request->validate([
           'name' => [
                'required',
                'string',
                'min:3',
                'max:10',
                'unique:guilds,name',
                'regex:/^[A-Za-z0-9_]+$/'
            ],
        ]);

        $player = auth()->user()->player;

        if($player->current_gold < Guild::GUILD_CREATION_COST) {
            return response()->json([
            'message' => 'Not enough gold.'
            ], 422);
        }

        $player->current_gold -= Guild::GUILD_CREATION_COST;

        $guild = Guild::create([
            'name' => $request->name,
            'leader_id' => $player->id,
            'gold_stash' => 0,
            'level' => 1
        ]);

        GuildMember::create([
            'guild_id' => $guild->id,
            'player_id' => $player->id,
            'rank' => 'Guild Leader',
            'guild_contribution' => 0,
            'joined_at' => now()
        ]);

        return response()->json([
            'message' => 'Guild created successfully!',
            'guild' => $guild
        ]);
    }
}
