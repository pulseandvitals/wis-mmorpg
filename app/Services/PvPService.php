<?php

namespace App\Services;

use App\Events\PvpStarted;
use App\Http\Resources\BattlePlayerResource;
use App\Models\Player;
use App\Models\PvpBattle;
use App\Models\Skill;

class PvPService
{
    public function processAction(Player $attacker, int $skillId)
    {
        // ======================================================
        // VALIDATE BATTLE
        // ======================================================
        if (!$attacker->in_pvp || !$attacker->pvp_battle_id) {
            return response()->json(['message' => 'You are not in battle.'], 403);
        }

        $battle = PvpBattle::findOrFail($attacker->pvp_battle_id);

        if ($battle->status !== 'active') {
            return response()->json(['message' => 'Battle already finished.'], 403);
        }

        // ======================================================
        // GET OPPONENT
        // ======================================================
        $defenderId = $battle->challenger_id === $attacker->id
            ? $battle->opponent_id
            : $battle->challenger_id;

        $defender = Player::findOrFail($defenderId);

        // ======================================================
        // VALIDATE TURN
        // ======================================================
        if ($battle->turn_player_id !== $attacker->id) {
            return response()->json(['message' => 'Not your turn.'], 403);
        }

        // ======================================================
        // GET ATTACKER SKILL (ONLY REAL PLAYER INPUT)
        // ======================================================
        $skill = Skill::findOrFail($skillId);

        // ======================================================
        // OPPONENT MUST ALSO USE THEIR OWN SKILL (NO AI)
        // If opponent has not played yet, we simulate "defense turn empty"
        // ======================================================
        $opponentSkill = null;

        // ======================================================
        // BUILD EVENTS
        // ======================================================
        $events = [];

        $events[] = $this->calculateAttack($attacker, $defender, $skill);

        // optional: only if you want simultaneous exchange
        // (PvP feel: both hit per turn)
        if ($opponentSkill) {
            $events[] = $this->calculateAttack($defender, $attacker, $opponentSkill);
        }

        // ======================================================
        // APPLY EVENTS
        // ======================================================
        foreach ($events as $event) {
            if ($event['target'] === $attacker->id) {
                $attacker->current_health -= $event['damage'];
            } else {
                $defender->current_health -= $event['damage'];
            }
        }

        $attacker->current_health = max(0, $attacker->current_health);
        $defender->current_health = max(0, $defender->current_health);

        // ======================================================
        // SAVE
        // ======================================================
        $attacker->save();
        $defender->save();

        // ======================================================
        // CHECK WIN CONDITION
        // ======================================================
        if ($attacker->current_health <= 0 || $defender->current_health <= 0) {

            $winner = $attacker->current_health > 0 ? $attacker : $defender;

            $battle->update([
                'status' => 'finished',
                'winner_id' => $winner->id,
            ]);

            $this->endBattle($attacker, $defender);

            return [
                'events' => $events,
                'player_hp' => $attacker->current_health,
                'opponent_hp' => $defender->current_health,
                'battle_ended' => true,
                'winner_id' => $winner->id,
                'log' => "{$winner->name} won the battle!"
            ];
        }

        // ======================================================
        // SWITCH TURN
        // ======================================================
        $battle->update([
            'turn_player_id' => $defender->id,
        ]);

        return [
            'events' => $events,
            'player_hp' => $attacker->current_health,
            'opponent_hp' => $defender->current_health,
            'battle_ended' => false,
            'next_turn' => $defender->id,
            'log' => "{$attacker->name} used {$skill->name}"
        ];
    }

    // ======================================================
    // CORE DAMAGE LOGIC (PvP ONLY)
    // ======================================================
    private function calculateAttack(Player $attacker, Player $defender, Skill $skill)
    {
        $base = $attacker->total_attack + $skill->damage;

        $crit = rand(1, 100) <= $attacker->total_critical_percentage;
        if ($crit) {
            $base *= 1.5;
        }

        $missChance = max(40, 90 - ($defender->total_evasion_percentage * 0.6));
        $miss = rand(1, 100) > $missChance;

        if ($miss) {
            return [
                'actor' => $attacker->id,
                'target' => $defender->id,
                'skill_name' => $skill->name,
                'damage' => 0,
                'crit' => false,
                'miss' => true,
            ];
        }

        $damage = $base - ($defender->total_defense * 0.55);

        return [
            'actor' => $attacker->id,
            'target' => $defender->id,
            'skill_name' => $skill->name,
            'damage' => max(1, (int)$damage),
            'crit' => $crit,
            'miss' => false,
        ];
    }

    // ======================================================
    // END BATTLE CLEANUP
    // ======================================================
    private function endBattle(Player $p1, Player $p2)
    {
        $p1->update([
            'in_pvp' => false,
            'pvp_battle_id' => null,
            'is_stunned' => false,
        ]);

        $p2->update([
            'in_pvp' => false,
            'pvp_battle_id' => null,
            'is_stunned' => false,
        ]);
    }

    // ======================================================
    // REQUEST BATTLE
    // ======================================================
    public function requestBattle(Player $attacker, int $targetId)
    {
        $defender = Player::findOrFail($targetId);

        if ($attacker->in_pvp || $defender->in_pvp) {
            return response()->json(['message' => 'One player is already in battle.'], 403);
        }

        if ($attacker->id === $defender->id) {
            return response()->json(['message' => 'Invalid target.'], 403);
        }

        $battle = PvpBattle::create([
            'challenger_id' => $attacker->id,
            'opponent_id' => $defender->id,
            'status' => 'active',
            'turn_player_id' => $attacker->id,
        ]);

        $attacker->update([
            'in_pvp' => true,
            'pvp_battle_id' => $battle->id,
        ]);

        $defender->update([
            'in_pvp' => true,
            'pvp_battle_id' => $battle->id,
        ]);

        broadcast(new PvpStarted($battle, $attacker, $defender));

        return [
            'message' => 'Battle request sent.',
        ];
    }
}
