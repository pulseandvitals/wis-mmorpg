<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Skill;

class PvPService
{
    /**
     * MAIN ENTRY
     */
    public function processAction(Player $attacker, $defenderId, $skillId)
    {
        $defender = Player::findOrFail($defenderId);
        $skill = Skill::findOrFail($skillId);

        $events = [];

        // =========================
        // PLAYER ATTACK
        // =========================
        $playerEvent = $this->calculateAttack($attacker, $defender, $skill);
        $events[] = $playerEvent;

        $defender->current_health -= $playerEvent['damage'];
        if ($defender->current_health < 0) {
            $defender->current_health = 0;
        }

        // CHECK WIN (PLAYER WINS)
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

        // =========================
        // OPPONENT AUTO ATTACK
        // =========================
        $enemySkill = $this->getRandomSkill($defender);

        $opponentEvent = $this->calculateAttack($defender, $attacker, $enemySkill);
        $events[] = $opponentEvent;

        $attacker->current_health -= $opponentEvent['damage'];
        if ($attacker->current_health < 0) {
            $attacker->current_health = 0;
        }

        $attacker->save();
        $defender->save();

        // CHECK WIN (OPPONENT WINS)
        if ($attacker->current_health <= 0) {
            return $this->buildResponse(
                $attacker,
                $defender,
                $events,
                "{$defender->name} defeated {$attacker->name}",
                $defender->id
            );
        }

        // =========================
        // CONTINUE FIGHT
        // =========================
        return $this->buildResponse(
            $attacker,
            $defender,
            $events,
            "{$attacker->name} clashed with {$defender->name}",
            $attacker->id
        );
    }

    // ======================================================
    // DAMAGE CALCULATION (MATCH YOUR PLAYERRESOURCE FIELDS)
    // ======================================================
    private function calculateAttack($attacker, $defender, $skill)
    {
        $baseDamage = $attacker->total_attack + $skill->damage;

        // CRIT
        $crit = $this->isCritical($attacker->total_critical_percentage);
        if ($crit) {
            $baseDamage *= 1.5;
        }

        // MISS CHECK
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

        // DEFENSE REDUCTION
        $finalDamage = $this->applyDefense($baseDamage, $defender->total_defense);

        return [
            "actor" => $attacker->id,
            "skill_name" => $skill->name,
            "damage" => (int) $finalDamage,
            "crit" => $crit,
            "miss" => false
        ];
    }

    // ======================================================
    // DEFENSE SYSTEM
    // ======================================================
    private function applyDefense($damage, $defense)
    {
        $reduction = $defense * 0.55;
        $result = $damage - $reduction;

        if ($result <= 0) {
            return rand(3, 6);
        }

        return $result;
    }

    // ======================================================
    // CRIT SYSTEM
    // ======================================================
    private function isCritical($critRate)
    {
        return rand(1, 100) <= $critRate;
    }

    // ======================================================
    // MISS SYSTEM
    // ======================================================
    private function isMiss($eva)
    {
        $hitChance = max(40, 90 - $eva * 0.6);
        return rand(1, 100) > $hitChance;
    }

    // ======================================================
    // RANDOM ENEMY SKILL
    // ======================================================
    private function getRandomSkill($player)
    {
        return $player->skills()->inRandomOrder()->first();
    }

    // ======================================================
    // RESPONSE FORMAT (FRONTEND READY)
    // ======================================================
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
