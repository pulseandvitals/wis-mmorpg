<?php

namespace App\Http\Controllers;

use App\Events\ZoneStateUpdated;
use App\Http\Resources\GuildResource;
use App\Models\Guild;
use App\Models\GuildMember;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuildController extends Controller
{
    public function getGuilds()
    {
        $guilds = Guild::query()
            ->orderByDesc('gold_stash')
            ->get();

        return response()->json([
            'guilds' => GuildResource::collection($guilds)
        ]);
    }

    public function myGuild()
    {
        $player = auth()->user()->player;
        $myGuild = Guild::find($player->guild_id);

        if(!$myGuild) {
            return response()->json([
                'guild' => null
            ]);
        }

        $myGuild->load('members.player');

        return response()->json([
            'guild' => $myGuild ? GuildResource::make($myGuild) : null
        ]);
    }

    public function joinGuild(Request $request)
    {
        $player = auth()->user()->player;

        if($player->guild_id) {
            return response()->json([
                'message' => 'You already have guild.'
            ],422);
        }
        $guild = Guild::find($request->guild['id']);

        if(!$guild) {
            return response()->json([
                'message' => 'Guild not found.'
            ],404);
        }

        if($guild->members->count() >= Guild::MAX_MEMBER) {
            return response()->json([
                'message' =>'Guild has reached its maximum capacity.'
            ],422);
        }

        $player->guild_id = $guild->id;
        $player->save();

        $guild->members()->create([
            'player_id' => $player->id,
            'rank' => 'Member',
            'gold_contribution' => 0,
            'joined_at' => now()
        ]);

        broadcast(new ZoneStateUpdated($player->current_map_id,[
            'id' => $player->id,
            'type' => 'player.update',
            'guild' => $player->guild,
        ]));

        return response()->json([
            'message' => "You joined {$guild->name}!",
            'guild' => $guild ? GuildResource::make($guild) : null
        ]);
    }

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
            'payment_type' => ['required','in:diamond,gold']
        ]);

        $player = auth()->user()->player;

        if($request->payment_type === 'gold' && $player->current_gold < Guild::GUILD_CREATION_COST) {
            return response()->json([
            'message' => 'Not enough gold.'
            ], 422);
        }

        if($request->payment_type === 'diamond' && $player->current_gold < Guild::GUILD_CREATION_COST_DIAMOND) {
            return response()->json([
                'message' => 'Not enough diamond.'
            ], 422);
        }

        if($request->payment_type === 'gold') {
            $player->current_gold -= Guild::GUILD_CREATION_COST;
        }

        if($request->payment_type === 'diamond') {
            $player->current_diamond -= Guild::GUILD_CREATION_COST_DIAMOND;
        }

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

        $player->guild_id = $guild->id;
        $player->save();

        broadcast(new ZoneStateUpdated($player->current_map_id,[
            'id' => $player->id,
            'type' => 'player.update',
            'guild' => $player->guild,
        ]));

        return response()->json([
            'message' => 'Guild created successfully!',
            'guild' => $guild
        ]);
    }

    public function contributeGuild(Request $request)
    {
        $player = auth()->user()->player;
        $playerContribute = (int) $request->contribute;

        if($player->current_gold < $playerContribute) {
            return response()->json([
            'message' => 'Not enough gold. Enter an exact amount.'
            ], 422);
        }

        $guild = Guild::find($player->guild_id);

        if(!$guild) {
            return response()->json([
            'message' => 'Guild not found.'
            ], 422);
        }

        $member = $guild->members()->where('player_id', $player->id)->first();

        if(!$member) {
            return response()->json([
                'message' => 'Member not found.'
            ], 422);
        }

        $member->gold_contribution += $playerContribute;
        $member->save();

        $guild->gold_stash += $playerContribute;
        $guild->save();

        $player->current_gold -= $playerContribute;
        $player->save();

        return response()->json([
            'message' => 'Contributed!',
            'guild' => $guild->load('members')
        ]);
    }

    public function applyGuildIcon(Request $request)
    {
        $request->validate([
            'icon' => 'required|image|max:2048',
        ]);

        if (!$request->hasFile('icon')) {
            return response()->json([
                'message' => 'No file uploaded'
            ], 422);
        }

        $player = auth()->user()->player;

        $file = $request->file('icon');

        $guild = $player->guild;

        $isFirstUpload = empty($guild->icon);

        if (!$isFirstUpload) {
            if ($player->current_diamond < Guild::COST_FOR_MULTIPLE_FILE_UPLOAD) {
                return response()->json([
                    'message' => 'Not enough diamond.'
                ], 422);
            }

            $player->current_diamond -= Guild::COST_FOR_MULTIPLE_FILE_UPLOAD;
            $player->save();
        }

        $path = "guild_icons/{$guild->id}";
        $file_name = Str::random(16) . '.' . $file->getClientOriginalExtension();

        $file->move(public_path($path), $file_name);

        $guild->icon = url("{$path}/{$file_name}");
        $guild->save();

        return response()->json([
            'message' => 'Guild icon updated successfully',
            'guild' => $guild
        ]);
    }

    public function leaveGuild()
    {
        $player = auth()->user()->player;

        if(!$player->guild) {
            return response()->json([
                'message' => 'Not enough diamond.'
            ], 422);
        }

        GuildMember::where('player_id', $player->id)
            ->where('guild_id', $player->guild_id)
            ->delete();

        $player->guild_id = null;
        $player->save();

        return response()->json([
            'message' => 'You leave guild.',
            'guild' => null
        ]);
    }
}
