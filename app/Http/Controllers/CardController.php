<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\Card;
use App\Models\Inventory;
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

    public function useCard(Request $request)
    {
        $request->validate([
            'card' => 'required|array',
        ]);

        $player = auth()->user()->player;

        $inventoryItem = Inventory::where('player_id', $player->id)
            ->where('id', $request->card['id'])
            ->with('card')
            ->first();

        if (!$inventoryItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Card not found in inventory'
            ], 404);
        }

        if (!$inventoryItem->card) {
            return response()->json([
                'status' => 'error',
                'message' => 'Card not found in inventory'
            ], 404);
        }

        // Ensure this is gear
        if ($inventoryItem->item_type !== 'card') {
            return response()->json([
                'status' => 'error',
                'message' => 'This item is not equippable'
            ], 400);
        }

        if($inventoryItem->is_equipped) {
            return response()->json([
                'status' => 'error',
                'message' => 'This card is already equipped'
            ], 400);
        }

        if($player->current_level < $inventoryItem->card->requirement_level) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not level enough to equip this card'
            ], 400);
        }

        $card = $inventoryItem->card;
        // helmet -> helmet_id
        // weapon -> weapon_id
        // armor -> armor_id
        $column = 'card_slot_'.$card->card_slot;

        $oldCardId = $player->$column;

        if ($oldCardId) {
            Inventory::where('player_id', $player->id)
                ->where('id', $oldCardId)
                ->where('item_type', 'card')
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
            'message' => $card->name . ' equipped successfully',
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
