<?php

namespace App\Http\Controllers;

use App\Http\Resources\GearResource;
use App\Models\CraftingMaterial;
use App\Models\Gear;
use App\Models\Inventory;
use App\Models\Material;
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

    public function getCraftingMaterials()
    {
        return CraftingMaterial::all()->map(function ($row) {
            return [
                'requirement_level' => $row->requirement_level,
                'materials' => json_decode($row->materials, true),
            ];
        });
    }

   public function craftGear(Request $request)
    {
        $player = auth()->user()->player;
        $recipe = CraftingMaterial::where('requirement_level', $request->gear['requirement_level'])->first();

        if (!$recipe) {
            return response()->json(['message' => 'Material not found'], 404);
        }

        // 🔥 decode JSON materials
        $materials = json_decode($recipe->materials, true);

        foreach ($materials as $material) {
            $item = Material::where('name', $material['item'])->first();
            if(!$item) {
                return response()->json(['message' => 'Material not found'], 404);
            }

            $inventoryItem = $player->inventory()
                ->where('item_id', $item->id)
                ->where('item_type','material')
                ->first();

            if (!$inventoryItem || $inventoryItem->quantity < $material['qty']) {
                return response()->json([
                    'message' => "Not enough {$material['item']}"
                ], 400);
            }
        }

        // ✅ deduct materials AFTER validation
        foreach ($materials as $material) {
            $player->inventory()
                ->where('item_id', $item->id)
                ->decrement('quantity', $material['qty']);
        }


        $uniqueStats = $this->randomStatGenerator();
        // 🔥 give crafted gear (example)
        $player->inventory()->create([
            'item_id' => $request->gear['id'],
            'quantity' => 1,
            'item_type' => 'gear',
            'random_stat' => $uniqueStats
        ]);

        return response()->json([
            'message' => 'Craft successful!'
        ]);
    }

    private function randomStatGenerator()
    {
        $stats = [];

        $statPool = [
            'atk' => ['min' => 5, 'max' => 10, 'chance' => 8],
            'def' => ['min' => 3, 'max' => 10, 'chance' => 8],
            'crit' => ['min' => 1, 'max' => 5, 'chance' => 3],
            'evasion' => ['min' => 1, 'max' => 5, 'chance' => 3],
            'speed' => ['min' => 1, 'max' => 10, 'chance' => 3],
            'hp' => ['min' => 1, 'max' => 20, 'chance' => 12],
            'mp' => ['min' => 1, 'max' => 20, 'chance' => 12],
        ];

        foreach ($statPool as $statName => $data) {

            $roll = rand(1, 100);

            // chance check
            if ($roll <= $data['chance']) {
                $stats[$statName] = rand($data['min'], $data['max']);
            }
        }

        return json_encode($stats);
    }

    public function upgradeGear(Request $request)
    {
        $request->validate([
            'gear' => 'required|string',
        ]);
        $key = $request->gear . '_id';

        $player = auth()->user()->player;

        if(!$player->{$key}) {
            return response()->json([
                'message' => 'Item not found.'
            ], 400);
        }

        $inventory = Inventory::with('gear')->findOrFail($player->{$key});

        // prevent invalid upgrade
        if ($inventory->item_type !== 'gear') {
            return response()->json([
                'message' => 'This item is not gear.'
            ], 400);
        }

        $currentLevel = $inventory->enhancement ?? 0;

        // max upgrade cap
        if ($currentLevel >= 10) {
            return response()->json([
                'message' => 'Max enhancement reached.'
            ], 400);
        }

        // cost formula
        $cost = 1000 * ($currentLevel + 1);

        if ($player->gold < $cost) {
            return response()->json([
                'message' => 'Not enough gold.'
            ], 400);
        }

        // success rate (decreases as level increases)
        $successRate = max(10, 100 - ($currentLevel * 10));
        $roll = rand(1, 100);

        if ($roll <= $successRate) {

            // SUCCESS UPGRADE
            $inventory->enhancement_level = $currentLevel + 1;
            $inventory->save();

            $player->gold -= $cost;
            $player->save();

            return response()->json([
                'message' => 'Upgrade successful!',
                'level' => $inventory->enhancement_level
            ]);
        }

        /**
         * FAIL CASE
         * Now we check if item breaks
         */
        $breakChance = max(5, $currentLevel * 5);
        $breakRoll = rand(1, 100);

        if ($breakRoll <= $breakChance) {

            // 💥 ITEM BREAKS
            $inventory->enhancement_level = 0;
            $inventory->save();

            $player->gold -= $cost;
            $player->save();

            return response()->json([
                'message' => 'Item enhancement back to 0 during upgrade!',
                'broken' => true
            ]);
        }

        // ❌ SAFE FAIL (no upgrade, no break)
        $player->gold -= $cost;
        $player->save();

        return response()->json([
            'message' => 'Upgrade failed, but item is safe.',
            'level' => $inventory->enhancement
        ]);
    }
}
