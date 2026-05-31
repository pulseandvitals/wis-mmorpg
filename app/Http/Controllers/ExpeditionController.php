<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Expedition;
use App\Models\Map;
use App\Models\Material;
use App\Models\Monster;
use Illuminate\Http\Request;

class ExpeditionController extends Controller
{
    public function myExpedition()
    {
        $player = auth()->user()->player;

        $expedition = Expedition::where('player_id',$player->id)
            ->where('status','progress')->first();

        return response()->json([
            'expedition' => $expedition
        ]);
    }

    public function startExpedition(Request $request)
    {
        $player = auth()->user()->player;

        if (!$player->is_online) {
            return response()->json([
                'status' => 'error',
                'message' => 'You must be online to start expedition'
            ], 422);
        }

        $request->validate([
            'map_name' => 'required|string',
            'hours' => 'required|integer|min:1|max:12',
        ]);

        // Prevent multiple expeditions
        if ($player->activity_status) {
            return response()->json([
                'status' => 'error',
                'message' => 'You already have an active expedition'
            ], 422);
        }

        $mapName = $request->map_name;
        $hours = $request->hours;
        $map = Map::where('name', $mapName)->first();

        if (!$map) {
            return response()->json([
                'status' => 'error',
                'message' => 'Map not found'
            ], 404);
        }

        $mapMonsters = Monster::where('map', $mapName)->get();

        if ($mapMonsters->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No monsters found in this map'
            ], 422);
        }

        $totalMonsters = $request->hours * 30;

        $totalMonsters = $hours * 30;

        $loot = [];
        $totalExp = 0;
        $totalGold = 0;

        for ($i = 0; $i < $totalMonsters; $i++) {

            $monster = $mapMonsters->random();

            // EXP
            $totalExp += (int) $monster->exp;

            // GOLD (optional if you want later)
            $totalGold += (int) ($monster->gold ?? 0);

            // DROPS
            $drops = json_decode($monster->drops, true);

            if (!empty($drops)) {
                foreach ($drops as $drop) {

                    $chance = (float) $drop['chance'];
                    $roll = mt_rand(1, 10000) / 100;

                    if ($roll <= $chance) {

                        $itemName = $drop['item'];

                        // 👇 KEY CHANGE: stack items instead of pushing
                        if (!isset($loot[$itemName])) {
                            $loot[$itemName] = 0;
                        }

                        $loot[$itemName] += 1;
                    }
                }
            }
        }

        $formattedLoot = [];

        foreach ($loot as $item => $qty) {
            $formattedLoot[] = [
                'item' => $item,
                'quantity' => $qty
            ];
        }

        $hoursBySecond = 3600 * $hours;
        $expedition = Expedition::create([
            'player_id' => $player->id,
            'map_name' => $mapName,
            'hours' => $hours,
            'exp_reward' => $totalExp,
            'gold_reward' => $totalExp,
            'drops' => json_encode($formattedLoot),
            'ends_at' => $hoursBySecond
        ]);

        $player->activity_status = "expedition";
        $player->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Expedition started successfully',
            'expedition' => $expedition
        ]);
    }

    public function claimExpedition(Request $request)
    {
        $player = auth()->user()->player;

        $expedition = Expedition::find($request->expedition['id']);

        if(!$expedition) {
            return response()->json([
                'status' => 'error',
                'message' => 'No expedition found.'
            ], 422);
        }

        $player->current_gold += $request->expedition['gold_reward'];
        $player->current_experience += $request->expedition['exp_reward'];
        $player->activity_status = null;
        $player->save();

        $drops = json_decode($expedition->drops, true);
        foreach ($drops as $drop) {
            $itemName = $drop['item'];
            $quantity = $drop['quantity'] ?? 1;


            $isCard = str_contains(strtolower($itemName), 'card');
            if ($isCard) {
                $item = Card::where('name', $itemName)->first();
                $itemType = 'card';
            } else {
                $item = Material::where('name',$itemName)->first();
                $itemType = 'material';
            }

            if (!$item) continue;


            $inventory = \App\Models\Inventory::where('player_id', $player->id)
                ->where('item_id', $item->id)
                ->first();

            if ($inventory) {
                $inventory->quantity += $quantity;
                $inventory->save();
            } else {
                \App\Models\Inventory::create([
                    'player_id' => $player->id,
                    'item_id' => $item->id,   // ✅ FIXED
                    'item_type' => $itemType,
                    'quantity' => $quantity,
                ]);
            }
        }

        $expedition->status = "claimed";
        $expedition->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Expedition reward claimed successfully',
            'expedition' => null
        ]);
    }

    public function cancelExpedition()
    {
        $player = auth()->user()->player;

        Expedition::where('player_id', $player->id)
            ->where('status', 'progress')
            ->update([
                'status' => 'cancelled'
        ]);

        return response()->json([
            'status' => "success",
            'message' => "Cancelled expedition",
            "expedition" => null,
        ]);
    }
}
