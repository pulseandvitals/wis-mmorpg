<?php

namespace App\Http\Controllers;

use App\Http\Resources\GearResource;
use App\Models\Gear;
use Illuminate\Http\Request;

class GearController extends Controller
{
    public function getWeapons()
    {
        $weapons = Gear::query()
            ->where('type', 'weapon')
            ->orderBy('requirement_level')
            ->get();

        return response()->json([
            'status' => 'success',
            'weapons' => GearResource::collection($weapons)
        ]);
    }

    public function getArmors()
    {
        $armors = Gear::query()
            ->whereIn('type',['boots','helmet','armor','gloves','ring','shield','pants'])
            ->orderBy('requirement_level')
            ->get();

        return response()->json([
            'status' => 'success',
            'armors' => GearResource::collection($armors)
        ]);
    }
}
