<?php

namespace App\Services;

use App\Events\PvpResolved;
use App\Events\PvpStarted;
use App\Events\ZoneStateUpdated;
use App\Models\Player;
use App\Models\PvpBattle;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;

class PvPService
{
    public function processAction(Player $player, int $skillId)
    {
        // ======================================================
        // VALIDATE BATTLE
        // ======================================================
        if (!$player->in_pvp || !$player->pvp_battle_id) {
            return response()->json(['message' => 'You are not in battle.'], 403);
        }

        $battle = PvpBattle::findOrFail($player->pvp_battle_id);

        if ($battle->status !== 'active') {
            return response()->json(['message' => 'Battle already finished.'], 403);
        }

        $isChallenger = $battle->challenger_id === $player->id;

        // ======================================================
        // STORE INPUT (NO RESOLVE YET)
        // ======================================================
        if ($isChallenger) {
            $battle->challenger_skill_id = $skillId;
        } else {
            $battle->opponent_skill_id = $skillId;
        }

        $battle->save();

        if (!$battle->challenger_skill_id || !$battle->opponent_skill_id) {
            return [
                'waiting' => true,
                'message' => 'Waiting for opponent...',
            ];
        }

        // ======================================================
        // HARD LOCK (ONLY ONE PROCESS CAN ENTER)
        // ======================================================
        return DB::transaction(function () use ($battle) {

            $battle = PvpBattle::where('id', $battle->id)
                ->lockForUpdate()
                ->first();

            // 🔒 if already resolved this round, stop duplicates
            if ($battle->status !== 'active') {
                return ['waiting' => true];
            }

            // OPTIONAL SAFETY FLAG (VERY IMPORTANT)
            if ($battle->round_processing ?? false) {
                return ['waiting' => true];
            }

            $battle->round_processing = true;
            $battle->save();

            // reload players
            $challenger = Player::find($battle->challenger_id);
            $opponent = Player::find($battle->opponent_id);

            $challengerSkill = Skill::find($battle->challenger_skill_id);
            $opponentSkill = Skill::find($battle->opponent_skill_id);

            // calculate
            $challengerEvent = $this->calculateAttack($challenger, $opponent, $challengerSkill);
            $opponentEvent = $this->calculateAttack($opponent, $challenger, $opponentSkill);

            if (!isset($challengerEvent) || !isset($opponentEvent)) {
                return ['waiting' => true];
            }

            // apply damage (ONLY ONCE)
            $challenger->current_health = max(0, $challenger->current_health - ($opponentEvent['damage'] ?? 0));
            $opponent->current_health = max(0, $opponent->current_health - ($challengerEvent['damage'] ?? 0));

            $challenger->save();
            $opponent->save();

            // winner
            $winner = $challenger->current_health > 0 ? $challenger : $opponent;

            if ($challenger->current_health <= 0 || $opponent->current_health <= 0) {
                $battle->status = 'finished';
                $battle->winner_id = $winner->id;
                $this->endBattle($challenger, $opponent);
            }

            // reset round inputs
            $battle->challenger_skill_id = null;
            $battle->opponent_skill_id = null;

            $battle->round_processing = false;
            $battle->round++;

            $battle->save();

            // broadcast (ONLY ONCE)
            broadcast(new PvpResolved(
                $battle,
                $challengerEvent,
                $opponentEvent,
                $challenger->current_health,
                $opponent->current_health
            ));

            return [
                'ok' => true
            ];
        });
    }

    // ======================================================
    // DAMAGE CALCULATION (UNCHANGED CORE LOGIC)
    // ======================================================
    private function calculateAttack(Player $attacker, Player $defender, Skill $skill)
    {
        $base = $attacker->total_attack + $skill->damage + rand(1, 20);

        // =========================
        // CRITICAL HIT
        // =========================
        $crit = rand(1, 100) <= $attacker->total_critical_percentage;
        if ($crit) {
            $critMultiplier = rand(130, 180) / 100;
            $base = (int) ($base * $critMultiplier);
        }

        // =========================
        // MISS CALCULATION
        // =========================
        $missChance = min(30, $defender->total_evasion_percentage);
        $miss = rand(1, 100) < $missChance;

        if ($miss) {
            return [
                'actor' => $attacker->id,
                'target' => $defender->id,
                'skill_name' => $skill->name,
                'damage' => 0,
                'crit' => false,
                'miss' => true,
                'stun' => false,
                'stun_duration' => 0,
            ];
        }

        // =========================
        // DAMAGE CALCULATION
        // =========================
        $reduction = ($defender->total_defense * 0.55) / ($defender->total_defense * 0.55 + 100);

        $damage = $base * (1 - $reduction);
        $damage = max(1, $damage);
        // =========================
        // STUN SYSTEM (FROM PLAYER STAT)
        // =========================
        $stunChance = $defender->total_stun_percentage ?? 0;

        $isStunned = $stunChance > 0
            ? (rand(1, 100) <= $stunChance)
            : false;

        if ($isStunned) {
            return [
                'actor' => $attacker->id,
                'target' => $defender->id,
                'skill_name' => $skill->name,
                'damage' => 0,
                'crit' => false,
                'miss' => false,
                'stun' => true,
                'stun_duration' => 1,
            ];
        }

        //Final damage stats
        return [
            'actor' => $attacker->id,
            'target' => $defender->id,
            'skill_name' => $skill->name,
            'damage' => max(1, (int)$damage),
            'crit' => $crit,
            'miss' => false,
            'stun' => false,
            'stun_duration' => 0,
        ];
    }

    // ======================================================
    // END BATTLE
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

        broadcast(new ZoneStateUpdated($p1->current_map_id,[
            'id' => $p1->id,
            'type' => 'player.update',
            'current_health' => $p1->current_health,
            'in_pvp' => 0,
        ]));

        broadcast(new ZoneStateUpdated($p2->current_map_id,[
            'id' => $p2->id,
            'type' => 'player.update',
            'current_health' => $p2->current_health,
            'in_pvp' => 0,
        ]));
    }

    // ======================================================
    // REQUEST BATTLE
    // ======================================================
    public function requestBattle(Player $attacker, int $targetId)
    {
        $defender = Player::findOrFail($targetId);
        $player = auth()->user()->player;

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
            'round' => 1,
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

        broadcast(new ZoneStateUpdated($player->current_map_id,[
            'id' => $player->id,
            'type' => 'player.update',
            'opponent_id' => $defender->id,
            'in_pvp' => 1,
        ]));

        return [
            'message' => 'Battle started',
        ];
    }
}
