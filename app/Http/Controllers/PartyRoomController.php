<?php

namespace App\Http\Controllers;

use App\Models\PartyMember;
use App\Models\PartyRoom;
use Illuminate\Http\Request;

class PartyRoomController extends Controller
{
    public function getParty()
    {
        $player = auth()->user()->player;

        $room = PartyRoom::with(['members.player'])
            ->whereHas('members', function ($q) use ($player) {
                $q->where('player_id', $player->id);
            })
            ->first();

        return response()->json($room->fresh('members.player'));
    }

    public function createRoom()
    {
        $player = auth()->user()->player;

        $room = PartyRoom::create([
            'code' => rand(10000,99999)
        ]);

        PartyMember::create([
            'party_room_id' => $room->id,
            'player_id' => $player->id,
            'joined_at' => now()
        ]);


        return response()->json($room->fresh('members.player'));
    }

    public function leaveRoom($roomId)
    {
        $player = auth()->user()->player;

        PartyMember::where('party_room_id', $roomId)
            ->where('player_id', $player->id)
            ->delete();

        $room = PartyRoom::with('members')->find($roomId);

        // Auto delete if empty
        if ($room->members->count() === 0) {
            $room->delete();
            return;
        }
    }

    public function joinRoom($code)
    {
        $player = auth()->user()->player;

        if (!$code) {
            return response()->json(['message' => 'Code is required'], 422);
        }

        $room = PartyRoom::with('members.player')
            ->where('code', $code)
            ->first();

        if ($room->members->count() >= 3) {
            return response()->json(['error' => 'Room full'], 422);
        }

        PartyMember::create([
            'party_room_id' => $room->id,
            'player_id' => $player->id,
            'joined_at' => now()
        ]);

        return response()->json($room->fresh('members.player'));
    }
}
