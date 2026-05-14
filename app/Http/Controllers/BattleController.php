<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\Experience;
use App\Models\Inventory;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BattleController extends Controller
{
    protected $player;

    public function __construct()
    {
        $this->player = auth()->user()->player;
    }

    public function saveBattle(Request $request)
    {
        $monsters = $request->monsters;
        $this->insertDropstoInventory($request->drops);

        $totalExp = 0;
        foreach ($monsters as $monster) {
            $totalExp += $monster['exp'] ?? 10;
        }
        $this->player->current_experience += $totalExp;
        $levelUp = false;
        /* SIMPLE LEVEL SYSTEM */
        $neededExp = Experience::whereLevel($this->player->current_level)->value('required_experience');
        if ($this->player->current_experience >= $neededExp) {
            $this->player->current_level += 1;

            $this->player->current_experience -= $neededExp;
            $this->player->total_attack += 3;
            $this->player->total_defense += 3;
            $this->player->max_health += 10;
            $this->player->max_mana += 10;
            $levelUp = true;
        }

        $this->player->current_health = $request->player['current_health'];
        $this->player->current_mana = $request->player['current_mana'];
        $this->player->total_attack += 3;
        $this->player->total_defense += 3;
        $this->player->save();

        return response()->json([
            'success' => true,
            'exp_gained' => $totalExp,
            'level_up' => $levelUp,
            'player' => PlayerResource::make($this->player),
        ]);
    }

    private function insertDropstoInventory($drops = null)
    {
        foreach ($drops as $item => $qty) {
            $getItem = Material::whereName($item)->first();
            Inventory::updateOrInsert(
                [
                    'player_id' => $this->player->id,
                    'item_id' => $getItem->id,
                    'item_type' => 'material'
                ],
                [
                    'quantity' => DB::raw("COALESCE(quantity, 0) + {$qty}")
                ]
            );
        }
    }
}
