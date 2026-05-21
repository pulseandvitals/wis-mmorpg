<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            'id' => $this->id,
            'name' => $this->name,
            'is_exp_potion_active' => $this->is_exp_potion_active,
            'class_type' => $this->class_type,
            'current_level' => $this->current_level,
            'current_experience' => $this->getExpPercentage(),
            'current_gold' => $this->current_gold,
            'current_diamond' => $this->current_diamond,
            'current_health' => $this->current_health,
            'current_mana' => $this->current_mana,

            'max_health' => $this->getAllStats()['hp'],
            'max_mana' => $this->getAllStats()['mp'],
            'total_attack' => $this->getAllStats()['attack'],
            'total_defense' => $this->getAllStats()['defense'],
            'total_speed' => $this->getAllStats()['speed'],
            'total_evasion_percentage' => $this->getAllStats()['evasion'],
            'total_critical_percentage' => $this->getAllStats()['crit'],

            'current_map_id' => $this->current_map_id,
            'x' => $this->x,
            'y' => $this->y,


            'active_buff_effects' => $this->active_buff_effects,

            /*
            |--------------------------------------------------------------------------
            | EQUIPPED GEARS
            |--------------------------------------------------------------------------
            */

            'helmet' => $this->helmet,
            'weapon' => $this->weapon,
            'armor' => $this->armor,
            'boots' => $this->boots,
            'gloves' => $this->gloves,
            'shield' => $this->shield,
            'necklace' => $this->necklace,
            'ring' => $this->ring,
            'pants' => $this->pants,
            'wings' => $this->wing,

            /*
            |--------------------------------------------------------------------------
            // | EQUIPPED GEAR IDS
            // |--------------------------------------------------------------------------
            // */

            // 'helmet_id' => $this->helmet_id,
            // 'weapon_id' => $this->weapon_id,
            // 'armor_id' => $this->armor_id,
            // 'boots_id' => $this->boots_id,
            // 'gloves_id' => $this->gloves_id,
            // 'shield_id' => $this->shield_id,
            // 'necklace_id' => $this->necklace_id,
            // 'pants_id' => $this->pants_id,
            // 'ring_id' => $this->ring_id,
        ];
    }
}
