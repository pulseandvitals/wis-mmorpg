<script setup>
import { ref } from "vue";

/* =========================
PROPS
========================= */
const props = defineProps({
    player: Object,
});

/* =========================
STATE
========================= */
const showBattleModal = ref(false);

const selectedTarget = ref(null);

const enemyPlayer = ref(null);

const isLockPhase = ref(false);

const turnTimer = ref(5);

const selectedSkill = ref(null);

const logs = ref([]);

const playerDamage = ref(null);
const enemyDamage = ref(null);

const battleId = ref(null);

const battleEnded = ref(false);

let interval = null;

/* =========================
SELECT PLAYER
========================= */
function selectPlayer(player) {
    selectedTarget.value = player;
}

/* =========================
START PvP
========================= */
function initiateHostile() {
    if (!selectedTarget.value) return;

    axios
        .post("/pvp/hostile", {
            target_id: selectedTarget.value.id,
        })
        .then((res) => {
            openBattle(res.data.battle_id);
        });
}

/* =========================
OPEN BATTLE (SERVER DRIVEN)
========================= */
function openBattle(id) {
    battleId.value = id;

    axios.get(`/pvp/battle/${id}`).then((res) => {
        const battle = res.data;

        enemyPlayer.value = battle.enemy;
        logs.value = battle.logs || [];

        showBattleModal.value = true;

        startLockPhase(battle.lock_ends_at);
    });
}

/* =========================
LOCK PHASE TIMER (SERVER CONTROLLED)
========================= */
function startLockPhase(lockEndsAt) {
    isLockPhase.value = true;
    selectedSkill.value = null;

    clearInterval(interval);

    interval = setInterval(() => {
        const now = Date.now();
        const end = new Date(lockEndsAt).getTime();

        turnTimer.value = Math.max(0, Math.ceil((end - now) / 1000));

        if (turnTimer.value <= 0) {
            clearInterval(interval);
            lockPhaseEnd();
        }
    }, 1000);
}

/* =========================
LOCK SKILL (SEND TO BACKEND)
========================= */
function lockSkill(skill) {
    if (!isLockPhase.value) return;

    selectedSkill.value = skill;

    axios.post(`/pvp/battle/${battleId.value}/lock`, {
        skill_id: skill.id,
    });
}

/* =========================
END LOCK PHASE
========================= */
function lockPhaseEnd() {
    isLockPhase.value = false;

    resolveTurn();
}

/* =========================
RESOLVE TURN (SERVER ONLY LOGIC)
========================= */
function resolveTurn() {
    axios.post(`/pvp/battle/${battleId.value}/resolve`).then((res) => {
        applyBattleResult(res.data);

        if (!res.data.finished) {
            startLockPhase(res.data.lock_ends_at);
        } else {
            battleEnded.value = true;

            setTimeout(() => {
                showBattleModal.value = false;
            }, 2000);
        }
    });
}

/* =========================
APPLY SERVER RESULT
========================= */
function applyBattleResult(data) {
    logs.value = data.logs;

    playerDamage.value = null;
    enemyDamage.value = null;

    data.events.forEach((event) => {
        if (event.type === "damage") {
            if (event.target === "player") {
                enemyDamage.value = event.value;
            } else {
                playerDamage.value = event.value;
            }
        }

        if (event.type === "crit") {
            logs.value.unshift("CRITICAL HIT!");
        }
    });
}

/* =========================
CLEANUP
========================= */
function resetBattle() {
    clearInterval(interval);
}
</script>
<template>
    <div>
        <!-- WORLD PLAYER CLICK -->
        <div
            v-for="p in players"
            :key="p.id"
            @click="selectPlayer(p)"
            class="player"
        >
            {{ p.name }}
        </div>

        <button @click="initiateHostile">HOSTILE</button>

        <!-- PvP MODAL -->
        <div v-if="showBattleModal" class="pvp">
            <div class="timer" v-if="isLockPhase">⏱ {{ turnTimer }}</div>

            <!-- PLAYER -->
            <div>
                <h3>{{ player.name }}</h3>
                <div v-if="playerDamage">-{{ playerDamage }}</div>
            </div>

            <!-- ENEMY -->
            <div>
                <h3>{{ enemyPlayer.name }}</h3>
                <div v-if="enemyDamage">-{{ enemyDamage }}</div>
            </div>

            <!-- SKILLS -->
            <div>
                <button
                    v-for="skill in player.skills"
                    :key="skill.id"
                    @click="lockSkill(skill)"
                    :disabled="!isLockPhase"
                >
                    {{ skill.name }}
                </button>
            </div>

            <!-- LOGS -->
            <div>
                {{ logs[0] }}
            </div>
        </div>
    </div>
</template>
<style scoped>
.pvp {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.timer {
    color: yellow;
    font-size: 20px;
    margin-bottom: 10px;
}

.player {
    cursor: pointer;
    padding: 5px;
}
</style>
