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
            $request->skill_id
        );
    }

    public function pvpRequest(Request $request, PvPService $pvp)
    {
        $player = auth()->user()->player;

        return $pvp->requestBattle(
            $player,
            $request->target_id
        );
    }

    public function checkInvite()
    {
        $player = auth()->user()->player;

        if ($player->in_pvp && $player->pvp_battle_id) {
            return [
                'in_battle' => true,
                'battle_id' => $player->pvp_battle_id,
            ];
        }

        return [
            'in_battle' => false
        ];
    }
}
