<?php

namespace App\Http\Controllers;

use App\Models\Potion;
use Illuminate\Http\Request;

class PotionController extends Controller
{
    public function getPotions()
    {
        $potions = Potion::all();

        return response()->json([
            'status' => 'success',
            'potions' => $potions
        ]);
    }
}
