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

        // =========================
        // 1. PLAYER TURN
        // =========================
        $playerEvent = $this->calculateAttack($attacker, $defender, $skill);

        $events[] = $playerEvent;

        $defender->current_health -= $playerEvent['damage'];
        if ($defender->current_health < 0) {
            $defender->current_health = 0;
        }

        // CHECK WIN EARLY
        if ($defender->current_health <= 0) {
            $defender->save();
            $attacker->save();

            return $this->buildResponse(
                $attacker,
                $defender,
                $events,
                "{$attacker->name} defeated {$defender->name}",
                "player"
            );
        }

        // =========================
        // 2. OPPONENT TURN (AUTO)
        // =========================
        $enemySkill = $this->getRandomSkill($defender);

        $opponentEvent = $this->calculateAttack($defender, $attacker, $enemySkill);

        $events[] = $opponentEvent;

        $attacker->current_health -= $opponentEvent['damage'];
        if ($attacker->current_health < 0) {
            $attacker->current_health = 0;
        }

        // =========================
        // SAVE STATE
        // =========================
        $attacker->save();
        $defender->save();

        // =========================
        // RESPONSE
        // =========================
        return $this->buildResponse(
            $attacker,
            $defender,
            $events,
            "{$attacker->name} clashed with {$defender->name}",
            $attacker->id
        );
    }

    /* =========================
    ATTACK CALCULATION
    ========================= */
    private function calculateAttack($attacker, $defender, $skill)
    {
        $base = $attacker->attack + $skill->damage;

        $crit = $this->isCritical($attacker->crit);
        if ($crit) {
            $base *= 1.5;
        }

        $miss = $this->isMiss($defender->eva);

        if ($miss) {
            return [
                "actor" => $attacker->id,
                "skill_name" => $skill->name,
                "damage" => 0,
                "crit" => false,
                "miss" => true
            ];
        }

        $finalDamage = $this->applyDefense($base, $defender->def);

        return [
            "actor" => $attacker->id,
            "skill_name" => $skill->name,
            "damage" => (int) $finalDamage,
            "crit" => $crit,
            "miss" => false
        ];
    }

    /* =========================
    DEFENSE CALCULATION
    ========================= */
    private function applyDefense($damage, $defense)
    {
        $reduction = $defense * 0.55;
        $result = $damage - $reduction;

        if ($result <= 0) {
            return rand(3, 6);
        }

        return $result;
    }

    /* =========================
    CRIT
    ========================= */
    private function isCritical($critRate)
    {
        return rand(1, 100) <= $critRate;
    }

    /* =========================
    MISS
    ========================= */
    private function isMiss($eva)
    {
        $hitChance = max(40, 90 - $eva * 0.6);
        return rand(1, 100) > $hitChance;
    }

    /* =========================
    RANDOM SKILL
    ========================= */
    private function getRandomSkill($player)
    {
        return $player->skills()->inRandomOrder()->first();
    }

    /* =========================
    RESPONSE BUILDER
    ========================= */
    private function buildResponse($player, $opponent, $events, $log, $nextTurn)
    {
        return [
            "events" => $events,

            "player_hp" => $player->current_health,
            "opponent_hp" => $opponent->current_health,

            "next_turn" => $nextTurn,

            "log" => $log
        ];
    }
}
