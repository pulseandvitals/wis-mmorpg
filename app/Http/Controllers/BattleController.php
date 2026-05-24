<?php

namespace App\Http\Controllers;

use App\Events\PartySharedReward;
use App\Http\Resources\PlayerResource;
use App\Models\Card;
use App\Models\Experience;
use App\Models\Inventory;
use App\Models\Material;
use App\Models\PartyRoom;
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
        $totalGold = 0;
        foreach ($monsters as $monster) {
            $totalExp += $monster['exp'] ?? 10;
            $totalGold += $monster['exp'] * 0.20 ?? 10;
        }

        $this->sharedPartyExpAndGold($totalExp, $totalGold);


        /* SIMPLE LEVEL SYSTEM */
        $multiplier = $this->player->is_exp_potion_active ? 2 : 1;
        $this->player->current_experience += (int)($totalExp * $multiplier);
        $this->player->current_gold += $totalGold;

        $levelUp = false;
        $neededExp = Experience::whereLevel($this->player->current_level)->value('required_experience');
        if ($this->player->current_experience >= $neededExp) {
            $this->player->current_level += 1;
            $this->player->current_experience -= $neededExp;

            $this->player->recalculateStats();
            $levelUp = true;
        }

        $this->player->current_health = $request->player['current_health'];
        $this->player->current_mana = $request->player['current_mana'];
        $this->player->x = $request->player['x'];
        $this->player->y = $request->player['y'];
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
            $isCard = str_contains(strtolower($item), 'card');
           if ($isCard) {
                $getItem = Card::where('name', $item)->first();
                $itemType = 'card';
            } else {
                $getItem = Material::where('name', $item)->first();
                $itemType = 'material';
            }

            if (!$getItem) {
                return;
            }

            Inventory::updateOrInsert(
                [
                    'player_id' => $this->player->id,
                    'item_id'   => $getItem->id,
                    'item_type' => $itemType,
                ],
                [
                    'quantity' => DB::raw("COALESCE(quantity, 0) + {$qty}")
                ]
            );
        }
    }

    public function sharedPartyExpAndGold($totalExp = null, $totalGold = null)
    {
        $player = $this->player;

        $room = PartyRoom::whereHas('members', function ($q) use ($player) {
                $q->where('player_id', $player->id);
            })
            ->with('members.player')
            ->first();

        $sharedExp = intval($totalExp * 0.10);
        $sharedGold = intval($totalGold * 0.10);

        if ($room) {
            foreach ($room->members as $member) {

                $memberPlayer = $member->player;

                // ❌ SKIP SELF
                if ($memberPlayer->id === $player->id) {
                    continue;
                }
                if($player->current_map_id === $memberPlayer->current_map_id) {
                    $memberPlayer->current_experience += $sharedExp;
                    $memberPlayer->current_gold += $sharedGold;
                    $memberPlayer->save();

                    broadcast(new PartySharedReward([
                        'room_id' => $room->id,
                        'player_id' => $memberPlayer->id,
                        'exp' => $sharedExp,
                        'gold' => $sharedGold,
                    ]))->toOthers();
                }
            }
        }
    }
}
