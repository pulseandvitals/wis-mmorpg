<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\TopUp;
use Illuminate\Http\Request;

class TopUpController extends Controller
{
    public function getTopUps()
    {
        if(auth()->user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Theres something wrong..'
            ]);
        }

        $topups = TopUp::with('user')->get();

        return response()->json([
            'topups' => $topups
        ]);
    }

    public function approveTopUp(Request $request)
    {
        if(auth()->user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Theres something wrong..'
            ]);
        }

        $details = TopUp::find($request->id);

        if(!$details || !$details->user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Details not found.'
            ]);
        }

        $player = Player::find($details->user->player_id);

        if(!$player) {
            return response()->json([
                'status' => 'error',
                'message' => 'Player details not found.'
            ]);
        }

        $player->current_diamond += $details->amount_purchase;
        $player->save();

        $details->status = 'completed';
        $details->save();

        return response()->json([
            'message' => 'Purchase approve successfully.',
        ]);
    }

    public function submitTopUp(Request $request)
    {
        $user = auth()->user();
        if($request->promo['price'] < $request->amount_sent) {
            return response()->json([
                'status' => 'error',
                'message' => 'Theres something wrong.. Check your details and input again.'
            ]);
        }

        if(!$request->reference_number || !$request->amount_sent) {
            return response()->json([
                'status' => 'error',
                'message' => 'Theres something wrong.. Check your details and input again.'
            ]);
        }

        TopUp::create([
            'user_id' => $user->id,
            'reference_number' => $request->reference_number,
            'cash_amount' => $request->amount_sent,
            'amount_purchase' => $request->promo['diamonds'],
            'status' => 'pending'
        ]);

        return response()->json([
                'message' => 'Payment Successful! Please wait for manual verification. Thank you!'
            ]);
    }
}
