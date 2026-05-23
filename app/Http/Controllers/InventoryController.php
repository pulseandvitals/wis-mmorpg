<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryResource;
use App\Http\Resources\PlayerResource;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class InventoryController extends Controller
{
    public function openInventory()
    {
        $player = auth()->user()->player;
        $inventory = $player->inventory()
            ->where('is_equipped', false)
            ->with(['gear', 'material', 'potion'])
            ->get();

        return response()->json([
            'status' => 'success',
            'inventory' => InventoryResource::collection($inventory)
        ]);
    }

    public function useGear(Request $request)
    {
        $request->validate([
            'gear' => 'required|array',
        ]);

        $player = auth()->user()->player;

        $inventoryItem = Inventory::where('player_id', $player->id)
            ->where('id', $request->gear['id'])
            ->with('gear')
            ->first();

        if (!$inventoryItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gear not found in inventory'
            ], 404);
        }

        // Ensure this is gear
        if ($inventoryItem->item_type !== 'gear') {
            return response()->json([
                'status' => 'error',
                'message' => 'This item is not equippable'
            ], 400);
        }

        if($inventoryItem->is_equipped) {
            return response()->json([
                'status' => 'error',
                'message' => 'This gear is already equipped'
            ], 400);
        }

        if($player->current_level < $inventoryItem->gear->requirement_level) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not level enough to equip this gear'
            ], 400);
        }

        $gear = $inventoryItem->gear;
        // helmet -> helmet_id
        // weapon -> weapon_id
        // armor -> armor_id
        $column = $gear->type . '_id';

        // Check if player column exists
        $allowedTypes = [
            'helmet',
            'weapon',
            'armor',
            'boots',
            'gloves',
            'shield',
            'pants',
            'ring',
            'wing',
        ];

        if (!in_array($gear->type, $allowedTypes)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid gear type'
            ], 400);
        }

        $weaponName = strtolower($gear->name);

        $rules = [
            'crusader' => ['sword'],
            'knight'   => ['spear'],
            'wizard'   => ['staff'],
            'archer'   => ['bow'],
            'assassin' => ['dagger'],
        ];

        $class = strtolower($player->class_type);

        $allowedKeywords = $rules[$class] ?? [];

        $canEquip = false;

        foreach ($allowedKeywords as $keyword) {
            if (str_contains($weaponName, $keyword)) {
                $canEquip = true;
                break;
            }
        }

        if ($gear->type === 'weapon' && !$canEquip) {
            return response()->json([
                'status' => 'error',
                'message' => ucfirst($class) . ' cannot equip this weapon'
            ], 400);
        }


        /*
        |--------------------------------------------------------------------------
        | UNEQUIP OLD GEAR
        |--------------------------------------------------------------------------
        */

        $oldGearId = $player->$column;

        if ($oldGearId) {
            Inventory::where('player_id', $player->id)
                ->where('id', $oldGearId)
                ->where('item_type', 'gear')
                ->update([
                    'is_equipped' => false
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | EQUIP NEW GEAR
        |--------------------------------------------------------------------------
        */
        $player->$column = $inventoryItem->id;
        $player->save();
        $player->recalculateStats();

        $inventoryItem->update([
            'is_equipped' => true
        ]);


        return response()->json([
            'status' => 'success',
            'message' => $gear->name . ' equipped successfully',
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
            ]))
        ]);
    }
}
