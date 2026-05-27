<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\Inventory;
use App\Models\Potion;
use Illuminate\Http\Request;
use Nette\Schema\Schema;

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

    public function buyPotion(Request $request)
    {
       $request->validate([
            'potion' => 'required|array'
        ]);

        $player = auth()->user()->player;

        if($request->potion['sell_type'] === 'diamond' && $player->current_diamond < $request->potion['item_price']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not enough diamonds'
            ], 400);
        }

        if($request->potion['sell_type'] === 'gold' && $player->current_gold < $request->potion['item_price']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not enough gold'
            ], 400);
        }

        $inventoryItem = $player->inventory()->where('item_type', 'potion')->where('item_id', $request->potion['id'])->first();
        $type = $request->potion['sell_type'];

        if($inventoryItem) {
            $inventoryItem->quantity += 1;
            $inventoryItem->save();
        } else {
            $player->inventory()->create([
                'item_type' => 'potion',
                'item_id' => $request->potion['id'],
                'quantity' => 1,
            ]);
        }

        $player->{"current_$type"} -= $request->potion['item_price'];
        $player->save();


        // Implementation for buying a potion

        return response()->json([
            'status' => 'success',
            'message' => 'Potion bought successfully'
        ]);
    }

    public function usePotion(Request $request)
    {
        $request->validate([
            'potion' => 'required|array',
        ]);

        $player = auth()->user()->player;

        $inventoryItem = Inventory::where('player_id', $player->id)
            ->where('id', $request->potion['id'])
            ->where('item_type', 'potion')
            ->with('potion')
            ->first();

        if (!$inventoryItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Potion not found in inventory'
            ], 404);
        }

        // Ensure this is a potion
        if ($inventoryItem->item_type !== 'potion') {
            return response()->json([
                'status' => 'error',
                'message' => 'This item is not usable'
            ], 400);
        }

        $existing = $player->active_buff_effects ?? [];
        $newEffects = $inventoryItem->potion->effects ?? [];

        foreach ($newEffects as $new) {

        $found = false;

        foreach ($existing as &$old) {

            if (
                $old['stat'] === $new['stat'] &&
                $old['operation'] === $new['operation']
            ) {
                // ❗ NOT stacking → REPLACE instead
                $old['value'] = $new['value'];
                $old['value_type'] = $new['value_type'];
                $old['expires_at'] = time() + $new['duration'];

                $found = true;
                break;
            }
        }

            unset($old); // safety cleanup

            if (!$found) {
                $new['expires_at'] = time() + $new['duration'];
                $existing[] = $new;
            }
        }

        $player->active_buff_effects = $existing;
        $player->save();

        // Remove potion from inventory
        if ($inventoryItem->quantity > 1) {
            $inventoryItem->quantity -= 1;
            $inventoryItem->save();
        } else {
            $inventoryItem->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Potion used successfully',
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
}
