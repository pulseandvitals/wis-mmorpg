<?php

namespace App\Http\Controllers;

use App\Models\Monster;
use Illuminate\Http\Request;

class MiniEventController extends Controller
{
    public function miniEventBet(Request $request)
    {
        $player = auth()->user()->player;

        $currentGold = $player->current_gold ?: 0;

        if($player->daily_bet_chance <= 0) {
            return response()->json([
                'message' => 'You have used all your daily bet chances. Try again tomorrow!'
            ]);
        }

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

        $roll = rand(1, 100) <= 40;
        $amountBet = $request->input('amount');
        if($roll) {
            $player->current_gold += $amountBet;
            $player->daily_bet_chance = max(0, $player->daily_bet_chance - 1);
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

    public function miniEventTrivia(Request $request)
    {
        $player = auth()->user()->player;

        if($player->daily_trivia_chance <= 0) {
            return response()->json([
                'message' => 'You have used all your daily trivia chances. Try again tomorrow!'
            ]);
        }

        $monster = Monster::where('name', 'not like', '%(Elite)%')
                ->inRandomOrder()
                ->first();

        $player->daily_trivia_chance -= 1;
        $player->save();

        return response()->json([
            'question' => strtoupper(str_shuffle($monster->name)),
            'answer' => $monster->name,
            'hint' => $monster->map,
            'player' => $player,
        ]);
    }

    public function miniEventTriviaAnswer(Request $request)
    {
        $player = auth()->user()->player;
        $winPrize = 2000;
        if(strtolower($request->answer) !== strtolower($request->correct_answer)) {
            return response()->json([
                'message' => "Wrong! The correct answer is {$request->correct_answer}!",
            ]);
        }

        $player->current_gold += $winPrize;
        $player->save();

        return response()->json([
            'message' => "Correct! You got {$winPrize}! Congratulations!",
        ]);
    }
}
