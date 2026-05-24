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
}
