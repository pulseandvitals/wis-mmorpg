<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function saveMap($map)
    {
        $player = auth()->user()->player;
        if($player->current_map_id !== $map) {
            $player->current_map_id = $map;
            $player->save();
        }
    }
    public function recalculateStats()
    {
        $stats = [
            'attack' => 0,
            'defense' => 0,
            'speed' => 0,
            'hp' => 0,
            'mp' => 0,
            'evasion' => 0,
            'crit' => 0,
        ];

        $equipped = [
            $this->helmet,
            $this->weapon,
            $this->armor,
            $this->boots,
            $this->gloves,
            $this->shield,
            $this->necklace,
            $this->ring,
            $this->pants,
        ];

        foreach ($equipped as $slot) {
            if (!$slot || !$slot->gear) continue;

            $gear = $slot->gear;

            $basic = json_decode($gear->basic_stats ?? '{}', true);
            $random = json_decode($slot->random_stat ?? '{}', true);

            foreach (array_merge($basic, $random) as $key => $value) {
                switch ($key) {
                    case 'atk': $stats['attack'] += $value; break;
                    case 'def': $stats['defense'] += $value; break;
                    case 'speed': $stats['speed'] += $value; break;
                    case 'hp': $stats['hp'] += $value; break;
                    case 'mp': $stats['mp'] += $value; break;
                    case 'evasion': $stats['evasion'] += $value; break;
                    case 'crit': $stats['crit'] += $value; break;
                }
            }
        }

        $this->total_attack = $stats['attack'];
        $this->total_defense = $stats['defense'];
        $this->total_speed = $stats['speed'];
        $this->total_evasion_percentage = $stats['evasion'];
        $this->total_critical_percentage = $stats['crit'];

        $this->max_health = 100 + $stats['hp'];
        $this->max_mana = 50 + $stats['mp'];

        $this->save();
    }
}
