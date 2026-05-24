<?php

namespace App\Http\Controllers;

use App\Services\PvPService;
use Illuminate\Http\Request;

class PvPController extends Controller
{
    public function action(Request $request, PvPService $pvp)
    {
        $attacker = auth()->user()->player;

        return $pvp->processAction(
            $attacker,
            $request->opponent_id,
            $request->skill_id
        );
    }

    public function checkInvite()
    {
        $player = auth()->user()->player;

        if ($player->battle_opponent_id) {
            return [
                'in_battle' => true,
                'opponent_id' => $player->battle_opponent_id,
            ];
        }

        return ['in_battle' => false];
    }
}
