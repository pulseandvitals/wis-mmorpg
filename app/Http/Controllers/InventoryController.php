<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function openInventory()
    {
        $player = auth()->user()->player;
        $inventory = $player->inventory()
            ->with(['gear', 'material'])
            ->get();

        return response()->json([
            'status' => 'success',
            'inventory' => InventoryResource::collection($inventory)
        ]);
    }
}
