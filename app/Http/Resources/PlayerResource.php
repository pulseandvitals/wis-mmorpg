<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            // ======================================================
            // BASIC INFO
            // ======================================================
            'id' => $this->id,
            'name' => $this->name,
            'class_type' => $this->class_type,
            'is_online' => $this->is_online,
            'guild' => $this->guild,
            // ======================================================
            // LEVEL / PROGRESSION
            // ======================================================
            'current_level' => $this->current_level,
            'experience_percentage' => $this->getExpPercentage(),
            'current_experience' => $this->current_experience,

            // ======================================================
            // COMBAT STATS
            // ======================================================
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

            // ======================================================
            // ECONOMY
            // ======================================================
            'current_gold' => $this->current_gold,
            'current_diamond' => $this->current_diamond,

            // ======================================================
            // WORLD POSITION
            // ======================================================
            'current_map_id' => $this->current_map_id,
            'x' => $this->x,
            'y' => $this->y,
            'direction' => $this->direction,

            // ======================================================
            // EQUIPMENT
            // ======================================================
            'helmet' => $this->helmet,
            'armor' => $this->armor,
            'pants' => $this->pants,
            'gloves' => $this->gloves,
            'boots' => $this->boots,
            'weapon' => $this->weapon,
            'shield' => $this->shield,
            'ring' => $this->ring,
            'wing' => $this->wing,
            'card_1' => $this->cardSlot1,
            'card_2' => $this->cardSlot2,
            'card_3' => $this->cardSlot3,
            'card_4' => $this->cardSlot4,

            // ======================================================
            // SYSTEM / GAME STATE
            // ======================================================
            'in_pvp' => $this->in_pvp,
            'pvp_battle_id' => $this->pvp_battle_id,

            // ======================================================
            // BUFFS / TALENTS / CARDS
            // ======================================================
            'active_buff_effects' => $this->active_buff_effects,
            'selected_talent_skills' => $this->selected_talent_skills,
            // ======================================================
            // DAILY LIMITS / STATS
            // ======================================================
            'daily_bet_chance' => $this->daily_bet_chance,
            'daily_trivia_chance' => $this->daily_trivia_chance,
            'daily_mobs_kill' => $this->daily_mobs_kill,
            'daily_fishing_chance' => $this->daily_fishing_chance,
        ];
    }
}
