<?php

namespace App\Http\Controllers;

use App\Http\Resources\GearResource;
use App\Models\CraftingMaterial;
use App\Models\Gear;
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
            'atk' => ['min' => 5, 'max' => 15, 'chance' => 5],
            'def' => ['min' => 3, 'max' => 12, 'chance' => 5],
            'crit' => ['min' => 1, 'max' => 10, 'chance' => 2],
            'evasion' => ['min' => 1, 'max' => 10, 'chance' => 2],
            'speed' => ['min' => 1, 'max' => 10, 'chance' => 2],
            'hp' => ['min' => 1, 'max' => 10, 'chance' => 5],
            'mp' => ['min' => 1, 'max' => 10, 'chance' => 10],
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
}
