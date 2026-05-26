<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BattlePlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->resource;

        $spriteFolder = $data->wing
            ? $data->class_type . ' ' . optional($data->wing->gear)->name
            : $data->class_type;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'class_type' => $this->class_type,

            'max_health' => round($this->getAllStats()['hp']),
            'current_health' => $this->current_health,

            'max_mana' => round($this->getAllStats()['mp']),
            'current_mana' => $this->current_mana,

            'total_attack' => round($this->getAllStats()['attack']),
            'total_defense' => round($this->getAllStats()['defense']),
            'total_speed' => round($this->getAllStats()['speed']),

            'total_evasion_percentage' => round($this->getAllStats()['evasion']),
            'total_critical_percentage' => round($this->getAllStats()['crit']),
            'total_stun_percentage' => round($this->getAllStats()['stun']),

            'battle_gif' => "/sprites/" . trim($spriteFolder) . "/battle.gif",
            'attack_gif' => "/sprites/" . trim($spriteFolder) . "/attack.gif",
            'dead_gif' => "/sprites/" . trim($spriteFolder) . "/dead.gif",
        ];
    }
}
