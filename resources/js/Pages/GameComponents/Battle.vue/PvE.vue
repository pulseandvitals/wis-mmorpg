<script setup>
import { ref, computed, watch } from "vue";
const props = defineProps({
    player: {
        type: Object,
        required: true,
    },
    selectedMonster: {
        type: Object,
        default: null,
    },
});

const player = ref({
    name: "Archer",
    hp: 120,
    maxHp: 120,
    mp: 80,
    maxMp: 80,
    attack: 18,
    battle_gif: "sprites/Archer/idle-right.gif",
    attack_gif: "sprites/Archer/attack.gif",
    skills: [
        {
            id: 1,
            name: "Slash",
            damage: 18,
            mana: 5,
            targets: 1,
        },
        {
            id: 2,
            name: "Double Strike",
            damage: 14,
            mana: 10,
            targets: 2,
        },
        {
            id: 3,
            name: "Whirlwind",
            damage: 10,
            mana: 18,
            targets: 3,
        },
    ],
});

/* =========================================
BATTLE STATE
========================================= */
const showBattleModal = ref(false);
const battleMonsters = ref([]);
const isAttacking = ref(false);
const playerTurn = ref(true);
const battleEnded = ref(false);
const logs = ref([]);

/* =========================================
ALIVE MONSTERS
========================================= */
const aliveMonsters = computed(() => {
    return battleMonsters.value.filter((monster) => monster.hp > 0);
});

/* =========================================
OPEN BATTLE
========================================= */
function openBattle(monster) {
    showBattleModal.value = true;
    battleEnded.value = false;
    playerTurn.value = true;

    logs.value = [];

    player.value.hp = player.value.maxHp;
    player.value.mp = player.value.maxMp;

    const monsterCount = Math.random() < 0.5 ? 2 : 3;

    battleMonsters.value = Array.from({ length: monsterCount }, (_, index) => {
        const randomHp = monster.maxHp + Math.floor(Math.random() * 25);

        return {
            id: index + 1,
            name: monster.name,
            hp: randomHp,
            maxHp: randomHp,
            attack: monster.attack,
            gif: monster.battle_gif,
        };
    });

    logs.value.unshift(`${monster.name} and allies appeared`);
}

/* =========================================
USE SKILL
========================================= */
function useSkill(skill, selectedMonster = null) {
    if (!playerTurn.value) return;

    if (battleEnded.value) return;

    if (player.value.mp < skill.mana) {
        logs.value.unshift("Not enough mana");

        return;
    }

    let targets = [];

    /* SINGLE TARGET */
    if (skill.targets === 1) {
        if (!selectedMonster) return;

        if (selectedMonster.hp <= 0) return;

        targets = [selectedMonster];
    } else {
        /* MULTI TARGET */
        targets = aliveMonsters.value.slice(0, skill.targets);
    }

    player.value.mp -= skill.mana;
    attackAnimation();

    targets.forEach((monster) => {
        const damage = randomDamage(skill.damage - 4, skill.damage + 4);

        monster.hp -= damage;

        if (monster.hp < 0) {
            monster.hp = 0;
        }

        logs.value.unshift(
            `${player.value.name} used ${skill.name} on ${monster.name} for ${damage}`,
        );

        if (monster.hp <= 0) {
            logs.value.unshift(`${monster.name} defeated`);
        }
    });

    checkBattle();

    if (!battleEnded.value) {
        playerTurn.value = false;

        setTimeout(() => {
            monsterTurn();
        }, 1000);
    }
}

function attackAnimation() {
    isAttacking.value = true;

    setTimeout(() => {
        isAttacking.value = false;
    }, 1000);
}

/* =========================================
MONSTER TURN
========================================= */
function monsterTurn() {
    aliveMonsters.value.forEach((monster) => {
        const damage = randomDamage(monster.attack - 2, monster.attack + 2);

        player.value.hp -= damage;

        if (player.value.hp < 0) {
            player.value.hp = 0;
        }

        logs.value.unshift(`${monster.name} attacked for ${damage}`);
    });

    checkBattle();

    if (!battleEnded.value) {
        playerTurn.value = true;
    }
}

/* =========================================
CHECK BATTLE
========================================= */
function checkBattle() {
    if (player.value.hp <= 0) {
        battleEnded.value = true;

        logs.value.unshift("You were defeated");
    }

    if (aliveMonsters.value.length === 0) {
        battleEnded.value = true;

        logs.value.unshift("Victory!");
    }
}

/* =========================================
HELPERS
========================================= */
function closeBattle() {
    showBattleModal.value = false;
}

function randomDamage(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
</script>

<template>
    <div v-if="showBattleModal" class="pve-modal-wrapper">
        <div class="pve-modal">
            <!-- HEADER -->
            <div class="modal-header">
                <h2 class="battle-title">⚔️ BATTLE</h2>

                <button @click="closeBattle" class="close-btn">✕</button>
            </div>

            <!-- BODY -->
            <div class="battle-body">
                <!-- HERO -->
                <div class="player-section">
                    <div class="player-wrapper">
                        <img
                            :src="
                                isAttacking
                                    ? player.attack_gif
                                    : player.battle_gif
                            "
                            class="player-sprite"
                        />

                        <!-- PLAYER UI -->
                        <div class="player-ui">
                            <p class="player-name">
                                {{ player.name }}
                            </p>

                            <div class="player-stats">
                                <!-- HP -->
                                <div class="stat-row">
                                    <div class="stat-bar">
                                        <div
                                            class="stat-fill hp-fill"
                                            :style="{
                                                width:
                                                    (player.hp / player.maxHp) *
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
                                                    (player.mp / player.maxMp) *
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

                <!-- MONSTERS -->
                <div class="monsters-section">
                    <div
                        v-for="monster in battleMonsters"
                        :key="monster.id"
                        class="monster-card"
                    >
                        <img :src="monster.gif" class="monster-sprite" />

                        <!-- MONSTER UI -->
                        <div class="monster-ui">
                            <p class="monster-name">
                                {{ monster.name }}
                            </p>

                            <div class="monster-hp-bar">
                                <div
                                    class="monster-hp-fill"
                                    :style="{
                                        width:
                                            (monster.hp / monster.maxHp) * 100 +
                                            '%',
                                    }"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SKILLS -->
            <div class="skills-panel">
                <button
                    v-for="skill in player.skills"
                    :key="skill.id"
                    class="skill-btn"
                    :disabled="!playerTurn || battleEnded"
                >
                    {{ skill.name }}

                    <!-- SINGLE TARGET -->
                    <template v-if="skill.targets === 1">
                        <div class="target-buttons">
                            <button
                                v-for="monster in aliveMonsters"
                                :key="monster.id"
                                class="target-btn"
                                @click="useSkill(skill, monster)"
                            >
                                {{ monster.name }}
                            </button>
                        </div>
                    </template>

                    <!-- MULTI TARGET -->
                    <template v-else>
                        <div class="target-buttons">
                            <button class="target-btn" @click="useSkill(skill)">
                                Cast
                            </button>
                        </div>
                    </template>
                </button>
            </div>

            <!-- FOOTER -->
            <div class="battle-footer">
                <p class="log-text">
                    {{ logs[0] || "Ready to battle!" }}
                </p>

                <p class="turn-text">
                    {{ playerTurn ? "🗡️ Your Turn" : "⚔️ Enemy Turn" }}
                </p>
            </div>
        </div>
    </div>

    <!-- =========================================
    MONSTER SELECT
    ========================================= -->
    <!-- <div v-if="!showBattleModal" class="monster-select">
        <div
            v-for="monster in worldMonsters"
            :key="monster.id"
            class="select-card"
            @click="openBattle(monster)"
        >
            <img :src="monster.battle_gif" class="select-sprite" />

            <p class="select-name">
                {{ monster.name }}
            </p>
        </div>
    </div> -->
</template>

<style scoped>
/* =========================================
OVERLAY
========================================= */
.pve-modal-wrapper {
    position: absolute;
    inset: 0;

    background: rgba(0, 0, 0, 0.82);

    display: flex;
    align-items: center;
    justify-content: center;

    z-index: 9999;

    backdrop-filter: blur(4px);
}

/* =========================================
MODAL
========================================= */
.pve-modal {
    width: 92%;
    height: 88%;

    background: linear-gradient(to bottom, #141414, #0d0d0d);

    border: 2px solid rgba(251, 191, 36, 0.45);

    border-radius: 16px;

    overflow: hidden;

    display: flex;
    flex-direction: column;

    box-shadow:
        0 0 30px rgba(0, 0, 0, 0.7),
        inset 0 0 20px rgba(255, 255, 255, 0.03);

    font-family: "Press Start 2P", cursive;
}

/* =========================================
HEADER
========================================= */
.modal-header {
    height: 42px;

    border-bottom: 1px solid rgba(255, 255, 255, 0.05);

    display: flex;
    align-items: center;
    justify-content: center;

    position: relative;

    background: rgba(255, 255, 255, 0.02);
}

.battle-title {
    color: #fde047;
    font-size: 8px;

    letter-spacing: 1px;
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

    background: rgba(239, 68, 68, 0.2);

    color: #fca5a5;

    cursor: pointer;

    font-size: 9px;

    transition: 0.2s;
}

.close-btn:hover {
    background: rgba(239, 68, 68, 0.4);
}

/* =========================================
BODY
========================================= */
.battle-body {
    flex: 1;

    display: flex;

    overflow: hidden;

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
    height: 60%;

    background: linear-gradient(
        to bottom,
        transparent,
        rgba(251, 191, 36, 0.3),
        transparent
    );
}

/* PLAYER */
.player-wrapper {
    width: 100%;
    height: 100%;

    position: relative;

    display: flex;
    align-items: center;
    justify-content: center;
}

.player-sprite {
    width: 150px;
    height: 150px;

    object-fit: contain;

    image-rendering: pixelated;

    filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.08));
}

/* PLAYER UI */
.player-ui {
    position: absolute;

    bottom: 55px;
    left: 50%;

    transform: translateX(-50%);

    width: 105px;
}

.player-name {
    color: #fde047;

    font-size: 4px;

    text-align: center;

    margin-bottom: 3px;

    opacity: 0.9;
}

/* =========================================
HP / MP
========================================= */
.player-stats {
    display: flex;
    flex-direction: column;

    gap: 2px;
}

.stat-bar {
    width: 100%;
    height: 5px;

    background: rgba(0, 0, 0, 0.6);

    border-radius: 999px;

    overflow: hidden;

    border: 1px solid rgba(255, 255, 255, 0.04);
}

.stat-fill {
    height: 100%;
    transition: width 0.25s ease;
}

.hp-fill {
    background: linear-gradient(to right, #22c55e, #15803d);
}

.mp-fill {
    background: linear-gradient(to right, #3b82f6, #1d4ed8);
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

    gap: 2px;

    overflow-y: auto;
}

.monster-card {
    width: 100%;

    position: relative;

    display: flex;
    align-items: center;
    justify-content: center;
}

.monster-sprite {
    width: 150px;
    height: 150px;

    object-fit: contain;

    image-rendering: pixelated;

    filter: drop-shadow(0 0 6px rgba(255, 255, 255, 0.05));
}

/* MONSTER UI */
.monster-ui {
    position: absolute;

    bottom: 22px;
    left: 50%;

    transform: translateX(-50%);

    width: 90px;
}

.monster-name {
    color: #f87171;

    font-size: 4px;

    text-align: center;

    margin-bottom: 2px;

    opacity: 0.9;
}

.monster-hp-bar {
    width: 100%;
    height: 4px;

    background: rgba(0, 0, 0, 0.65);

    border-radius: 999px;

    overflow: hidden;

    border: 1px solid rgba(255, 255, 255, 0.04);
}

.monster-hp-fill {
    height: 100%;

    background: linear-gradient(to right, #ef4444, #991b1b);

    transition: width 0.25s ease;
}

/* =========================================
SKILLS
========================================= */
.skills-panel {
    border-top: 1px solid rgba(255, 255, 255, 0.05);

    padding: 8px;

    display: flex;
    justify-content: center;
    flex-wrap: wrap;

    gap: 6px;

    background: rgba(255, 255, 255, 0.015);
}

.skill-btn {
    min-width: 105px;

    background: rgba(51, 65, 85, 0.6);

    border: 1px solid rgba(255, 255, 255, 0.06);

    border-radius: 7px;

    color: white;

    padding: 6px;

    font-size: 5px;

    font-family: inherit;

    cursor: pointer;

    transition: 0.2s;
}

.skill-btn:hover {
    background: rgba(71, 85, 105, 0.7);
}

.skill-btn:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

/* TARGET BUTTONS */
.target-buttons {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;

    gap: 3px;

    margin-top: 5px;
}

.target-btn {
    background: rgba(239, 68, 68, 0.18);

    border: 1px solid rgba(239, 68, 68, 0.2);

    border-radius: 4px;

    color: #fecaca;

    padding: 3px 5px;

    font-size: 4px;

    font-family: inherit;

    cursor: pointer;

    transition: 0.2s;
}

.target-btn:hover {
    background: rgba(239, 68, 68, 0.35);
}

/* =========================================
FOOTER
========================================= */
.battle-footer {
    height: 32px;

    border-top: 1px solid rgba(255, 255, 255, 0.05);

    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 0 10px;

    background: rgba(255, 255, 255, 0.015);
}

.log-text {
    color: #d1d5db;

    font-size: 5px;

    opacity: 0.85;
}

.turn-text {
    color: #fde047;

    font-size: 5px;
}
.skill-inner {
    display: flex;
    align-items: center;
    gap: 6px;
    justify-content: center;
}

.skill-icon {
    width: 16px;
    height: 16px;

    image-rendering: pixelated;
}

.skill-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.skill-name {
    font-size: 5px;
    color: #fff;
}

.skill-meta {
    font-size: 4px;
    color: #94a3b8;
}

/* =========================================
MONSTER SELECT
========================================= */
.monster-select {
    position: absolute;
    inset: 0;

    background: rgba(0, 0, 0, 0.72);

    display: grid;
    grid-template-columns: repeat(2, 1fr);

    gap: 18px;

    padding: 20px;
}

.select-card {
    display: flex;
    flex-direction: column;

    align-items: center;
    justify-content: center;

    border: 2px solid #fbbf24;
    border-radius: 12px;

    background: rgba(0, 0, 0, 0.35);

    cursor: pointer;

    transition: 0.2s;
}

.select-card:hover {
    transform: scale(1.03);

    background: rgba(255, 255, 255, 0.05);
}

.select-sprite {
    width: 130px;
    height: 130px;

    object-fit: contain;
}

.select-name {
    color: #fde047;

    font-size: 8px;

    margin-top: 8px;
}
</style>
