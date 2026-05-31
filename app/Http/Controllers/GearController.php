<?php

namespace App\Http\Controllers;

use App\Http\Resources\GearResource;
use App\Http\Resources\PlayerResource;
use App\Models\CraftingMaterial;
use App\Models\Gear;
use App\Models\Inventory;
use App\Models\Material;
use App\Models\Player;
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

    public function getWings()
    {
        $wings = Gear::query()
            ->where('type', 'wing')
            ->get();

        return response()->json([
            'status' => 'success',
            'wings' => GearResource::collection($wings),
            'purchase_details' => [
                'price' => 999,
                'sell_type' => 'diamond'
            ],
        ]);
    }

    public function viewGear($id)
    {
        $player = Player::with([
            'helmet.gear',
            'weapon.gear',
            'armor.gear',
            'boots.gear',
            'gloves.gear',
            'shield.gear',
            'pants.gear',
            'ring.gear',
            'wing.gear',
            'cardSlot1.card',
            'cardSlot2.card',
            'cardSlot3.card',
            'cardSlot4.card'
        ])->find($id);

        if(!$player) {
            return response()->json(['error' => 'Character not found'], 404);
        }

        return response()->json([
            'gear' => PlayerResource::make($player)
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

    public function unequipGear(Request $request)
    {
        $player = auth()->user()->player;
        $inventoryItem = Inventory::find($request->slot['inventory_id']);

        if(!$inventoryItem) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        $itemKey = $request->slot['item_key'];
        $slot = match ($inventoryItem->item_type) {
            'card' => str_replace('card_', 'card_slot_', $itemKey),
            default => $itemKey . '_id',
        };

        $inventoryItem->is_equipped = false;
        $inventoryItem->save();

        $player->$slot = null;
        $player->save();

        return response()->json([
            'success' => true,
            'player' => new PlayerResource($player->fresh([
                'helmet.gear',
                'weapon.gear',
                'armor.gear',
                'boots.gear',
                'gloves.gear',
                'shield.gear',
                'necklace.gear',
                'ring.gear',
                'pants.gear',
                'wing.gear',
                'cardSlot1.card',
                'cardSlot2.card',
                'cardSlot3.card',
                'cardSlot4.card',
            ]))
        ]);
    }

   public function craftGear(Request $request)
    {
        $player = auth()->user()->player;
        $recipe = CraftingMaterial::where('requirement_level', $request->gear['requirement_level'])->first();
        dd($request->gear);
        dd($recipe);
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

            $item = Material::where('name', $material['item'])->first();

            if(!$item) {
                return response()->json(['message' => 'Material not found'], 404);
            }

            $player->inventory()
                ->where('item_id', $item->id)
                ->where('item_type','material')
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
            'attack' => ['min' => 5, 'max' => 10, 'chance' => 10],
            'defense' => ['min' => 3, 'max' => 10, 'chance' => 8],
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


        $material = $request->gear === 'weapon'
                ? Material::where('name', 'Celebeam Gem')->first()
                : Material::where('name', 'Seleri Gem')->first();

        $inventoryMaterial = Inventory::where('player_id', $player->id)
            ->where('item_id', $material->id)
            ->where('item_type', 'material')
            ->first();

        $inventory = Inventory::with('gear')->findOrFail($player->{$key});

        if(!$inventoryMaterial || $inventoryMaterial->quantity <= 0) {
            return response()->json([
                'message' => 'Upgrade material not found.'
            ], 404);
        }

        // prevent invalid upgrade
        if ($inventory->item_type !== 'gear') {
            return response()->json([
                'message' => 'This item is not gear.'
            ], 400);
        }

        $currentLevel = $inventory->enhancement_level ?? 0;

        // max upgrade cap
        if ($currentLevel >= 10) {
            return response()->json([
                'message' => 'Max enhancement reached.'
            ], 400);
        }

        // cost formula
        $cost = 1000 * ($currentLevel + 1);

        if ($player->current_gold < $cost) {
            return response()->json([
                'message' => 'Not enough gold.'
            ], 400);
        }

        // success rate (decreases as level increases)
        $successRate = max(5, 100 - ($currentLevel * 12));
        $roll = rand(1, 100);

        if ($roll <= $successRate) {

            // SUCCESS UPGRADE
            $inventory->enhancement_level = $currentLevel + 1;
            $inventory->save();

            $inventoryMaterial->decrement('quantity', 1);

            $player->current_gold -= $cost;
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
        $breakChance = max(8, min(70, $currentLevel * 7));
        $breakRoll = rand(1, 100);

        if ($breakRoll <= $breakChance) {

            // 💥 ITEM BREAKS
            $inventory->enhancement_level = 0;
            $inventory->save();

            $inventoryMaterial->decrement('quantity', 1);

            $player->current_gold -= $cost;
            $player->save();

            return response()->json([
                'message' => 'Item enhancement back to 0 during upgrade!',
                'broken' => true
            ]);
        }

        // ❌ SAFE FAIL (no upgrade, no break)

        $player->current_gold -= $cost;
        $player->save();

        $inventoryMaterial->decrement('quantity', 1);

        return response()->json([
            'message' => 'Upgrade failed, but item is safe.',
            'level' => $inventory->enhancement_level
        ]);
    }

    public function marketBuy(Request $request)
    {
        $request->validate([
            'gear' => 'required|array',
            'gear.id' => 'required|integer|exists:gears,id',
            'gear.type' => 'required|string',
            'purchase_details' => 'required|array',
            'purchase_details.price' => 'required|numeric',
            'purchase_details.sell_type' => 'required|string',
        ]);

        $player = auth()->user()->player;

        $gear = $request->gear;
        $purchaseFormat = $request->purchase_details['sell_type'];
        $price = $request->purchase_details['price'];

        $currencyField = $purchaseFormat === 'diamond'
            ? 'current_diamond'
            : 'current_gold';

        $playerCurrency = $player->$currencyField;

        if ($playerCurrency < $price) {
            return response()->json([
                'message' => "Not enough {$purchaseFormat}."
            ], 400);
        }

        /**
         * Deduct currency safely
         */
        $player->$currencyField -= $price;

        /**
         * Add item to inventory
         */
        $randomStats = $this->randomStatGenerator();

        $player->inventory()->create([
            'item_id' => $gear['id'],
            'item_type' => 'gear',
            'stats' => $randomStats
        ]);

        $player->save();

        return response()->json([
            'message' => 'Purchase successful!'
        ]);
    }
}
