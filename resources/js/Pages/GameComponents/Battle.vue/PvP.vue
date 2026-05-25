<script setup>
import { pushAlert } from "@/Stores/GlobalAlert";
import { ref } from "vue";

const props = defineProps({
    player: { type: Object, required: true },
});

const opponent = ref(null);

const showBattleModal = ref(false);
const battleEnded = ref(false);

const playerTurn = ref(true);
const playerShout = ref(null);
const opponentShout = ref(null);
const isHurt = ref(false);
const battleState = ref({
    locked: false,
});
const logs = ref([]);

// ======================================================
// REQUIRED REFS (FIXED MISSING VARIABLES)
// ======================================================
const activeActor = ref(null);
const skillEffect = ref(null);

const playerDamage = ref(null);
const opponentDamage = ref(null);

// ======================================================
// OPEN BATTLE
// ======================================================
function openPvPBattle(enemy) {
    showBattleModal.value = true;
    battleEnded.value = false;
    const spriteFolder = enemy.wing
        ? `${enemy.class_type} ${enemy.wing?.gear?.name}`
        : enemy.class_type;
    opponent.value = {
        ...enemy,
        current_health: enemy.max_health,
        dead_gif: `/sprites/${spriteFolder}/dead.gif`,
        battle_gif: `/sprites/${spriteFolder}/idle-left.gif`,
        attack_gif: `/sprites/${spriteFolder}/attack.gif`,
    };

    logs.value = [`${enemy.name} challenged you!`];
}

defineExpose({ openPvPBattle });

// ======================================================
// CALL BACKEND
// ======================================================
async function useSkill(skill) {
    if (battleState.value.locked || battleEnded.value) return;

    battleState.value.locked = true;

    try {
        const res = await axios.post("/pvp/action", {
            skill_id: skill.id,
        });

        await playEvents(res.data.events);

        props.player.current_health = res.data.player_hp;
        opponent.value.current_health = res.data.opponent_hp;
        pushAlert(res.data.message, "success");
        logs.value.unshift(res.data.log);

        if (res.data.battle_ended) {
            battleEnded.value = true;
        }

        checkBattle();
    } catch (e) {
        pushAlert(e.response.data.message, "error");
    } finally {
        battleState.value.locked = false;
    }
}

// ======================================================
// ANIMATION ENGINE (FIXED)
// ======================================================
function delay(ms) {
    return new Promise((r) => setTimeout(r, ms));
}

async function playEvents(events) {
    for (const event of events) {
        activeActor.value = event.actor;

        // ✅ SHOUT (skill name or custom text)
        if (event.actor === props.player.id) {
            playerShout.value = event.skill_name;
        } else {
            opponentShout.value = event.skill_name;
        }

        triggerSkillEffect({ name: event.skill_name });

        if (event.actor === props.player.id) {
            opponentDamage.value = formatDamage(event);
        } else {
            playerDamage.value = formatDamage(event);
            isHurt.value = true;
        }

        await delay(600);

        playerDamage.value = null;
        opponentDamage.value = null;

        // ✅ CLEAR SHOUT (important)
        playerShout.value = null;
        opponentShout.value = null;

        activeActor.value = null;
        isHurt.value = false;

        await delay(150);
    }
}
// ======================================================
// UI EFFECTS
// ======================================================
function formatDamage(e) {
    if (e.miss) return "MISS";
    return e.crit ? `${e.damage} CRIT` : `${e.damage}`;
}

function triggerSkillEffect(skill) {
    const skillName = skill.name
        .toLowerCase()
        .replace(/\s+/g, "-")
        .replace(/[^a-z-]/g, "");

    skillEffect.value = skillName;

    setTimeout(() => {
        skillEffect.value = null;
    }, 1000);
}

// ======================================================
// CHECK END
// ======================================================
function checkBattle() {
    if (props.player.current_health <= 0) {
        battleEnded.value = true;
        logs.value.unshift("You lost the duel");
    }

    if (opponent.value.current_health <= 0) {
        battleEnded.value = true;
        logs.value.unshift("You won the duel!");
    }
}

// ======================================================
// CLOSE
// ======================================================
function closeBattle() {
    showBattleModal.value = false;
}
</script>

<template>
    <div v-if="showBattleModal" class="pve-modal-wrapper">
        <div class="pve-modal">
            <!-- HEADER -->
            <div class="modal-header">
                <h2 class="battle-title">⚔️ PVP DUEL</h2>
                <button @click="closeBattle" class="close-btn">✕</button>
            </div>

            <!-- BODY -->
            <!-- <div class="bg-white">{{ activeActor }}</div> -->
            <div class="battle-body">
                <!-- PLAYER -->
                <div class="player-section">
                    <div class="player-wrapper">
                        <img
                            :src="
                                player.current_health <= 0
                                    ? player.dead_gif
                                    : activeActor === player.id
                                      ? player.attack_gif
                                      : player.battle_gif
                            "
                        />

                        <div class="player-floor"></div>
                        <div v-if="playerShout" class="shout-text player-shout">
                            {{ playerShout }}
                        </div>
                        <div
                            v-if="skillEffect && activeActor === opponent.id"
                            class="skill-effect"
                            :class="skillEffect"
                        ></div>

                        <div
                            v-if="playerDamage"
                            class="damage-text player-damage"
                        >
                            -{{ playerDamage }}
                        </div>

                        <div class="player-ui">
                            <p class="player-name">{{ player.name }}</p>

                            <div class="player-stats">
                                <!-- HP -->
                                <div class="stat-row">
                                    <div class="stat-bar">
                                        <div
                                            class="stat-fill hp-fill"
                                            :style="{
                                                width:
                                                    (player.current_health /
                                                        player.max_health) *
                                                        100 +
                                                    '%',
                                            }"
                                        />
                                    </div>
                                </div>

                                <!-- MP -->
                                <div class="stat-row">
                                    <div class="stat-bar">
                                        <div
                                            class="stat-fill mp-fill"
                                            :style="{
                                                width:
                                                    (player.current_mana /
                                                        player.max_mana) *
                                                        100 +
                                                    '%',
                                            }"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- OPPONENT (PvP version of monster section) -->
                <div class="player-section">
                    <div class="player-wrapper">
                        <img
                            :src="
                                opponent.current_health <= 0
                                    ? opponent.dead_gif
                                    : activeActor === opponent.id
                                      ? opponent.attack_gif
                                      : opponent.battle_gif
                            "
                            class="player-sprite"
                            :class="{
                                'monster-hurt': isHurt,
                            }"
                        />
                        <div v-if="playerShout" class="shout-text player-shout">
                            {{ playerShout }}
                        </div>

                        <div class="player-floor"></div>

                        <div
                            v-if="skillEffect && activeActor === player.id"
                            class="skill-effect"
                            :class="skillEffect"
                        ></div>

                        <div
                            v-if="opponentDamage"
                            class="damage-text monster-damage"
                        >
                            -{{ opponentDamage }}
                        </div>

                        <div class="player-ui">
                            <p class="player-name">{{ opponent.name }}</p>

                            <div class="player-stats">
                                <!-- HP -->
                                <div class="stat-row">
                                    <div class="stat-bar">
                                        <div
                                            class="stat-fill hp-fill"
                                            :style="{
                                                width:
                                                    (opponent.current_health /
                                                        opponent.max_health) *
                                                        100 +
                                                    '%',
                                            }"
                                        />
                                    </div>
                                </div>

                                <!-- MP -->
                                <div class="stat-row">
                                    <div class="stat-bar">
                                        <div
                                            class="stat-fill mp-fill"
                                            :style="{
                                                width:
                                                    (opponent.current_mana /
                                                        opponent.max_mana) *
                                                        100 +
                                                    '%',
                                            }"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SKILLS (UNCHANGED LOGIC) -->
            <div class="skills-panel">
                <div
                    v-for="(skill, i) in player.skills"
                    :key="skill.id"
                    class="skill-card"
                    :class="{ 'opacity-40': !playerTurn || battleEnded }"
                >
                    <div class="skill-icon-wrap" @click="useSkill(skill)">
                        <img :src="skill.icon" class="skill-icon" />
                    </div>

                    <p class="skill-name">{{ skill.name }}</p>

                    <button
                        class="skill-btn"
                        :disabled="!playerTurn || battleEnded"
                        @click="useSkill(skill)"
                    >
                        Cast ({{ i + 1 }})
                    </button>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="battle-footer">
                <p class="log-text">{{ logs[0] }}</p>
                <p class="turn-text">
                    {{ playerTurn ? "🗡️ Your Turn" : "⚔️ Opponent Turn" }}
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* =========================================
OVERLAY
========================================= */
.pve-modal-wrapper {
    position: absolute;
    inset: 0;

    /* LESS DARK / MORE TRANSPARENT */
    background: rgba(0, 0, 0, 0.55);

    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    z-index: 9999;

    backdrop-filter: blur(2px);
}

/* =========================================
MODAL
========================================= */
.pve-modal {
    width: 100%;
    height: 100%;

    /* TRANSPARENT GLASS EFFECT */
    background: rgba(15, 15, 15, 0.38);

    border: 1px solid rgba(255, 255, 255, 0.06);

    border-radius: 16px;

    overflow: hidden;

    display: flex;
    flex-direction: column;

    box-shadow:
        0 0 30px rgba(0, 0, 0, 0.45),
        inset 0 0 15px rgba(255, 255, 255, 0.02);

    backdrop-filter: blur(6px);
}

/* =========================================
HEADER
========================================= */
.modal-header {
    height: 42px;

    border-bottom: 1px solid rgba(255, 255, 255, 0.04);

    display: flex;
    align-items: center;
    justify-content: center;

    position: relative;

    /* MORE TRANSPARENT */
    background: rgba(255, 255, 255, 0.02);
}

.battle-title {
    color: #fde047;
    font-size: 10px;

    letter-spacing: 1px;

    opacity: 0.9;
}

.close-btn {
    position: absolute;

    right: 10px;
    top: 50%;

    transform: translateY(-50%);

    width: 22px;
    height: 22px;

    border: none;
    border-radius: 5px;

    background: rgba(255, 255, 255, 0.06);

    color: #fca5a5;

    cursor: pointer;

    font-size: 15px;

    transition: 0.2s;
}

.close-btn:hover {
    background: rgba(255, 255, 255, 0.12);
}

/* =========================================
BODY
========================================= */
.battle-body {
    flex: 1;

    display: flex;

    overflow: hidden;
    position: relative;
    padding: 10px 0;
}

/* =========================================
PLAYER SIDE
========================================= */
.player-section {
    width: 50%;
    height: 100%;

    position: relative;

    display: flex;
    align-items: center;
    justify-content: center;
}

/* DIVIDER */
.player-section::after {
    content: "";

    position: absolute;

    top: 50%;
    right: 0;

    transform: translateY(-50%);

    width: 1px;
    height: 55%;

    background: linear-gradient(
        to bottom,
        transparent,
        rgba(255, 255, 255, 0.12),
        transparent
    );
}

/* PLAYER */
.player-wrapper {
    width: 100%;
    height: 100%;

    position: relative;

    display: flex;
    flex-direction: column;

    align-items: center;
    justify-content: center;
}

/* SMALLER PLAYER */
.player-sprite {
    width: 70px;
    height: 70px;

    object-fit: contain;

    image-rendering: pixelated;
    position: relative;
    z-index: 2;

    filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.08));
}
.player-floor {
    position: absolute;
    bottom: 42%;
    left: 50%;
    transform: translateX(-50%);

    width: 160px; /* BIGGER */
    height: 60px; /* BIGGER */

    background: radial-gradient(
        ellipse at center,
        rgba(180, 0, 255, 0.35) 0%,
        rgba(120, 0, 255, 0.15) 35%,
        rgba(180, 0, 255, 0.05) 60%,
        transparent 80%
    );

    border-radius: 50%;

    filter: blur(2px);
    animation: auraPulse 2.5s ease-in-out infinite;
    z-index: 0;
}

@keyframes auraPulse {
    0%,
    100% {
        transform: translateX(-50%) scale(1);
        opacity: 0.7;
    }
    50% {
        transform: translateX(-50%) scale(1.15);
        opacity: 1;
    }
}
/* =========================================
MONSTERS
========================================= */
.monsters-section {
    width: 50%;
    height: 100%;

    display: flex;
    flex-direction: column;

    justify-content: center;
    align-items: center;

    gap: 8px;

    position: relative;
    overflow: hidden;
}

.monster-card {
    width: 100%;

    position: relative;

    display: flex;
    align-items: center;
    justify-content: center;
}

/* SMALLER MONSTER */
.monster-sprite {
    width: 95px;
    height: 95px;

    object-fit: contain;

    image-rendering: pixelated;

    filter: drop-shadow(0 0 6px rgba(255, 255, 255, 0.05));
}

/* MONSTER UI */
.monster-ui {
    position: absolute;

    bottom: -25px;
    left: 50%;

    transform: translateX(-50%);

    width: 90px;

    padding: 6px;

    border-radius: 10px;

    display: flex;
    flex-direction: column;
    align-items: center;

    gap: 3px;
}

.monster-name {
    color: #f87171;

    font-size: 10px;
    text-align: center;

    opacity: 0.95;
}

.monster-hp-bar {
    width: 80%;
    height: 4px;

    background: #f1f1f1;

    overflow: hidden;
}

/* FILL */
.monster-hp-fill {
    height: 100%;

    background: red;

    transition: width 0.25s ease;
}

/* =========================================
SKILLS
========================================= */
.skills-panel {
    border-top: 1px solid rgba(255, 255, 255, 0.04);

    padding: 8px;

    display: flex;
    justify-content: center;
    flex-wrap: wrap;

    gap: 6px;

    background: rgba(255, 255, 255, 0.015);
}

.skill-btn {
    min-width: 105px;

    background: rgba(255, 255, 255, 0.04);

    border: 1px solid rgba(255, 255, 255, 0.04);

    border-radius: 7px;

    color: white;

    padding: 6px;

    font-size: 10px;

    font-family: inherit;

    cursor: pointer;

    transition: 0.2s;

    backdrop-filter: blur(4px);
}

.skill-btn:hover {
    background: rgba(255, 255, 255, 0.08);
}

.skill-btn:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

/* SKILL CARD */
.skill-card {
    min-width: 110px;

    padding: 8px;

    display: flex;
    flex-direction: column;
    align-items: center;

    gap: 6px;

    background: rgba(255, 255, 255, 0.03);

    border: 1px solid rgba(255, 255, 255, 0.05);

    border-radius: 10px;

    backdrop-filter: blur(4px);

    transition: 0.2s;
}

.skill-card:hover {
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 0.06);
}

/* CENTER ICON */
.skill-icon-wrap {
    width: 64px;
    height: 64px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;
    background: rgba(0, 0, 0, 0.35);

    cursor: pointer;
    overflow: hidden;
}

/* NAME UNDER ICON */
.skill-name {
    font-size: 10px;
    color: #fde047;

    text-align: center;

    opacity: 0.9;
}

/* TARGET AREA */
.target-buttons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;

    gap: 3px;
}

.target-btn {
    font-size: 10px;

    padding: 3px 5px;

    border-radius: 4px;

    border: 1px solid rgba(255, 255, 255, 0.06);

    background: rgba(255, 255, 255, 0.05);

    color: #fecaca;

    cursor: pointer;

    transition: 0.2s;
}

.target-btn:hover {
    background: rgba(255, 255, 255, 0.12);
}

/* =========================================
FOOTER
========================================= */
.battle-footer {
    height: 32px;

    border-top: 1px solid rgba(255, 255, 255, 0.04);

    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 0 10px;

    background: rgba(255, 255, 255, 0.015);
}

.log-text {
    color: #d1d5db;

    font-size: 10px;

    opacity: 0.85;
}

.turn-text {
    color: #fde047;

    font-size: 10px;
}

.skill-inner {
    display: flex;
    align-items: center;
    gap: 6px;
    justify-content: center;
}

.skill-icon {
    width: 50px;
    height: 50px;

    image-rendering: pixelated;
}

.skill-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.skill-name {
    font-size: 15px;
    color: #fff;
}

.skill-meta {
    font-size: 10px;
    color: #94a3b8;
}

/* =========================================
MONSTER SELECT
========================================= */
.monster-select {
    position: absolute;
    inset: 0;

    background: rgba(0, 0, 0, 0.45);

    display: grid;
    grid-template-columns: repeat(2, 1fr);

    gap: 18px;

    padding: 20px;

    backdrop-filter: blur(4px);
}

.select-card {
    display: flex;
    flex-direction: column;

    align-items: center;
    justify-content: center;

    border: 1px solid rgba(255, 255, 255, 0.08);

    border-radius: 12px;

    background: rgba(255, 255, 255, 0.03);

    cursor: pointer;

    transition: 0.2s;

    backdrop-filter: blur(4px);
}

.select-card:hover {
    transform: scale(1.03);

    background: rgba(255, 255, 255, 0.06);
}

.select-sprite {
    width: 100px;
    height: 100px;

    object-fit: contain;
}

.select-name {
    color: #fde047;

    font-size: 10px;

    margin-top: 8px;
}

/* DAMAGE TEXT */
.damage-text {
    position: absolute;

    font-size: 25px;
    font-weight: bold;

    color: #ff4d4d;

    animation: damageFloat 1.2s ease-out forwards;

    pointer-events: none;

    z-index: 20;
}

/* PLAYER DAMAGE */
.player-damage {
    top: 45%;
    left: 50%;

    transform: translateX(-50%);
}

/* MONSTER DAMAGE */
.monster-damage {
    top: 22%;
    left: 50%;

    transform: translateX(-50%);
}

.monster-hurt {
    animation: monsterShake 0.25s linear;
}

@keyframes monsterShake {
    0% {
        transform: translateX(0);
    }
    20% {
        transform: translateX(-8px);
    }
    40% {
        transform: translateX(8px);
    }
    60% {
        transform: translateX(-6px);
    }
    80% {
        transform: translateX(6px);
    }
    100% {
        transform: translateX(0);
    }
}

/* FLOAT ANIMATION */
@keyframes damageFloat {
    0% {
        opacity: 0;
        transform: translate(-50%, 0px) scale(0.7);
    }

    20% {
        opacity: 1;
        transform: translate(-50%, -10px) scale(1.2);
    }

    50% {
        opacity: 1;
        transform: translate(-50%, -25px) scale(1.2);
    }

    100% {
        opacity: 0;
        transform: translate(-50%, -35px) scale(1);
    }
}

.shout-text {
    position: absolute;

    top: 230px;
    left: 50%;

    transform: translate(-50%, -120%);

    font-size: 15px;
    font-weight: bold;

    color: #f1f1f1;

    white-space: nowrap;

    pointer-events: none;

    z-index: 50;
}

.player-shout {
    color: #f1f1f1;
    background-color: rgb(0, 0, 0, 0.5);
    padding: 5px 10px;
}
.shout-text {
    position: absolute;

    top: 240px;
    left: 50%;

    transform: translate(-50%, -120%);

    font-size: 10px;
    font-weight: bold;

    color: #f1f1f1;

    white-space: nowrap;

    pointer-events: none;

    z-index: 50;
}
.monster-shout {
    top: 20%;
    color: #f1f1f1;
    padding: 5px 10px;
    background-color: rgb(0, 0, 0, 0.5);
}

@keyframes shoutPop {
    0% {
        opacity: 0;
        transform: translate(-50%, 10px) scale(0.8);
    }

    30% {
        opacity: 1;
        transform: translate(-50%, 0px) scale(1.2);
    }

    100% {
        opacity: 0;
        transform: translate(-50%, -10px) scale(1);
    }
}

.skill-effect {
    position: absolute;
    pointer-events: none;
    z-index: 50;
}

.player-ui {
    position: relative;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
}

.player-name {
    font-size: 10px;
    color: #f1f1f1;
    opacity: 0.9;
}

/* STATS CONTAINER (your compact style kept) */
.player-stats {
    width: 55px;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

/* ROW */
.stat-row {
    display: flex;
    align-items: center;
}

/* BAR */
.stat-bar {
    flex: 1;
    height: 5px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 2px;
    overflow: hidden;
}

/* FILL */
.stat-fill {
    height: 100%;
    transition: width 0.25s ease;
}

/* HP */
.hp-bar {
    border-color: rgba(231, 76, 60, 0.6);
}

.hp-fill {
    background: linear-gradient(to right, #e74c3c, #c0392b);
}

/* MP */
.mp-bar {
    border-color: rgba(52, 152, 219, 0.6);
}

.mp-fill {
    background: linear-gradient(to right, #3498db, #2980b9);
}

.skill-card.active-turn {
    box-shadow: 0 0 10px rgba(255, 215, 0, 0.4);
}
</style>
