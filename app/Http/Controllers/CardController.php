<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function getCards()
    {
        $cards = Card::all();

        return response()->json([
            'cards' => $cards,
        ]);
    }
}
