<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MiniEventController extends Controller
{
    public function miniEventBet(Request $request)
    {
        $player = auth()->user()->player;

        $currentGold = $player->current_gold ?: 0;

        if($currentGold < $request->input('amount')) {
            return response()->json([
                'message' => 'Not enough gold. You poor. Go farm!'
            ]);
        }

        if($request->input('amount') > 100000) {
            return response()->json([
                'message' => 'Above minimum bet. Too much gambling!'
            ]);
        }

        $roll = (bool) rand(0,1);
        $amountBet = $request->input('amount');
        if($roll) {
            $player->current_gold += $amountBet;
            $player->save();
            $amountWon = $amountBet * 2;
            return response()->json([
                'message' => "You won! You doubled your bet to {$amountWon}!",
                'result' => true,
                'reward' => $amountWon
            ]);
        } else {
            $player->current_gold -= $amountBet;
            $player->save();
            return response()->json([
                'message' => "Unfortunately, You lose -{$amountBet}!",
                'result' => false,
                'reward' => 0
            ]);
        }
    }
}
