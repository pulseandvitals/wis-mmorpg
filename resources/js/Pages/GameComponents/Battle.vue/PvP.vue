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

const enemySelectedSkill = ref(null);

const logs = ref([]);

const playerDamage = ref(null);
const enemyDamage = ref(null);

let interval = null;

const battleEnded = ref(false);

/* =========================
SELECT PLAYER IN WORLD
========================= */
function selectPlayer(player) {
    selectedTarget.value = player;
}

/* =========================
START HOSTILE PvP
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
LOAD BATTLE
========================= */
function openBattle(battleId) {
    axios.get(`/pvp/battle/${battleId}`).then((res) => {
        const battle = res.data;

        enemyPlayer.value = battle.enemy;

        logs.value = [];

        logs.value.push("PvP Duel Started!");

        startTurn();
    });

    showBattleModal.value = true;
}

/* =========================
TURN SYSTEM (5s LOCK)
========================= */
function startTurn() {
    isLockPhase.value = true;
    turnTimer.value = 5;
    selectedSkill.value = null;

    enemySelectedSkill.value =
        enemyPlayer.value.skills[
            Math.floor(Math.random() * enemyPlayer.value.skills.length)
        ];

    interval = setInterval(() => {
        turnTimer.value--;

        if (turnTimer.value <= 0) {
            clearInterval(interval);
            resolveTurn();
        }
    }, 1000);
}

/* =========================
LOCK SKILL
========================= */
function lockSkill(skill) {
    if (!isLockPhase.value) return;

    selectedSkill.value = skill;
}

/* =========================
RESOLVE TURN (SPEED ORDER)
========================= */
function resolveTurn() {
    isLockPhase.value = false;

    const pSpeed = props.player.current_speed;
    const eSpeed = enemyPlayer.value.current_speed;

    const first = pSpeed >= eSpeed ? "player" : "enemy";

    if (first === "player") {
        executePlayerAttack();
        setTimeout(executeEnemyAttack, 400);
    } else {
        executeEnemyAttack();
        setTimeout(executePlayerAttack, 400);
    }

    setTimeout(() => {
        checkBattle();

        if (!battleEnded.value) {
            setTimeout(startTurn, 800);
        }
    }, 1200);
}

/* =========================
DAMAGE SYSTEM
========================= */
function randomDamage(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function isCritical(rate = 0) {
    return Math.random() * 100 < rate;
}

/* =========================
PLAYER ATTACK
========================= */
function executePlayerAttack() {
    const skill = selectedSkill.value || props.player.skills[0];

    let damage = randomDamage(
        props.player.attack + skill.damage - 4,
        props.player.attack + skill.damage + 4,
    );

    if (isCritical(props.player.crit)) {
        damage = Math.floor(damage * 1.5);
        playerDamage.value = `${damage} CRIT`;
    } else {
        playerDamage.value = damage;
    }

    enemyPlayer.value.current_health -= damage;

    if (enemyPlayer.value.current_health < 0)
        enemyPlayer.value.current_health = 0;

    logs.value.unshift(`${props.player.name} used ${skill.name}`);

    setTimeout(() => {
        playerDamage.value = null;
    }, 800);
}

/* =========================
ENEMY ATTACK
========================= */
function executeEnemyAttack() {
    const skill = enemySelectedSkill.value;

    let damage = randomDamage(
        enemyPlayer.value.attack + skill.damage - 4,
        enemyPlayer.value.attack + skill.damage + 4,
    );

    if (isCritical(enemyPlayer.value.crit)) {
        damage = Math.floor(damage * 1.5);
        enemyDamage.value = `${damage} CRIT`;
    } else {
        enemyDamage.value = damage;
    }

    props.player.current_health -= damage;

    if (props.player.current_health < 0) props.player.current_health = 0;

    logs.value.unshift(`${enemyPlayer.value.name} used ${skill.name}`);

    setTimeout(() => {
        enemyDamage.value = null;
    }, 800);
}

/* =========================
CHECK BATTLE END
========================= */
function checkBattle() {
    if (props.player.current_health <= 0) {
        battleEnded.value = true;
        logs.value.unshift("You Lost");
        setTimeout(() => (showBattleModal.value = false), 2000);
    }

    if (enemyPlayer.value.current_health <= 0) {
        battleEnded.value = true;
        logs.value.unshift("You Win!");
        setTimeout(() => (showBattleModal.value = false), 2000);
    }
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
