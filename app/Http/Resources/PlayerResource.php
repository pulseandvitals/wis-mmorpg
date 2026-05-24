<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Skill;

class PvPService
{
    public function processAction(Player $attacker, $defenderId, $skillId)
    {
        $defender = Player::findOrFail($defenderId);
        $skill = Skill::findOrFail($skillId);

        $events = [];

        // ======================================================
        // PLAYER TURN
        // ======================================================
        $playerEvent = $this->calculateAttack($attacker, $defender, $skill);
        $events[] = $playerEvent;

        $defender->current_health -= $playerEvent['damage'];
        if ($defender->current_health < 0) {
            $defender->current_health = 0;
        }

        // ======================================================
        // STUN CHANCE (1 TURN)
        // ======================================================
        $this->applyStun($attacker, $defender, $events);

        // WIN CHECK
        if ($defender->current_health <= 0) {
            $defender->save();
            $attacker->save();

            return $this->buildResponse(
                $attacker,
                $defender,
                $events,
                "{$attacker->name} defeated {$defender->name}",
                $attacker->id
            );
        }

        // ======================================================
        // OPPONENT TURN
        // ======================================================

        if ($defender->is_stunned) {
            // SKIP TURN + REMOVE STUN AFTER USE
            $events[] = [
                "actor" => $defender->id,
                "skill_name" => null,
                "damage" => 0,
                "crit" => false,
                "miss" => false,
                "stun" => true,
                "skip_turn" => true
            ];

            $defender->is_stunned = false;
        } else {
            $enemySkill = $this->getRandomSkill($defender);

            $opponentEvent = $this->calculateAttack($defender, $attacker, $enemySkill);
            $events[] = $opponentEvent;

            $attacker->current_health -= $opponentEvent['damage'];

            if ($attacker->current_health < 0) {
                $attacker->current_health = 0;
            }
        }

        $attacker->save();
        $defender->save();

        // WIN CHECK
        if ($attacker->current_health <= 0) {
            return $this->buildResponse(
                $attacker,
                $defender,
                $events,
                "{$defender->name} defeated {$attacker->name}",
                $defender->id
            );
        }

        return $this->buildResponse(
            $attacker,
            $defender,
            $events,
            "{$attacker->name} clashed with {$defender->name}",
            $attacker->id
        );
    }

    // ======================================================
    // STUN SYSTEM (1 TURN ONLY)
    // ======================================================
    private function applyStun($attacker, $defender, &$events)
    {
        if (!$attacker->total_stun_percentage) return;

        if (rand(1, 100) <= $attacker->total_stun_percentage) {
            $defender->is_stunned = true;

            $events[] = [
                "actor" => $attacker->id,
                "skill_name" => "Stun",
                "damage" => 0,
                "crit" => false,
                "miss" => false,
                "stun" => true
            ];
        }
    }

    // ======================================================
    // DAMAGE CALCULATION
    // ======================================================
    private function calculateAttack($attacker, $defender, $skill)
    {
        $baseDamage = $attacker->total_attack + $skill->damage;

        $crit = $this->isCritical($attacker->total_critical_percentage);
        if ($crit) {
            $baseDamage *= 1.5;
        }

        $miss = $this->isMiss($defender->total_evasion_percentage);

        if ($miss) {
            return [
                "actor" => $attacker->id,
                "skill_name" => $skill->name,
                "damage" => 0,
                "crit" => false,
                "miss" => true
            ];
        }

        $finalDamage = $this->applyDefense($baseDamage, $defender->total_defense);

        return [
            "actor" => $attacker->id,
            "skill_name" => $skill->name,
            "damage" => (int) $finalDamage,
            "crit" => $crit,
            "miss" => false
        ];
    }

    private function applyDefense($damage, $defense)
    {
        $reduction = $defense * 0.55;
        $result = $damage - $reduction;

        return $result <= 0 ? rand(3, 6) : $result;
    }

    private function isCritical($critRate)
    {
        return rand(1, 100) <= $critRate;
    }

    private function isMiss($eva)
    {
        $hitChance = max(40, 90 - $eva * 0.6);
        return rand(1, 100) > $hitChance;
    }

    private function getRandomSkill($player)
    {
        return $player->skills()->inRandomOrder()->first();
    }

    private function buildResponse($player, $opponent, $events, $log, $nextTurn)
    {
        return [
            "events" => $events,
            "player_hp" => $player->current_health,
            "opponent_hp" => $opponent->current_health,
            "next_turn" => $nextTurn,
            "log" => $log,
        ];
    }
}
