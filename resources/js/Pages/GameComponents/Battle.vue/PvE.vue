<script setup>
import { ref, computed } from "vue";
import Monster from "./Monster.vue";
import "./skill_animations.css";
import LootDrops from "./LootDrops.vue";
import LevelUp from "./LevelUp.vue";

const props = defineProps({
    player: {
        type: Object,
        required: true,
    },
    playerSkills: {
        type: Object,
        required: true,
    },
    selectedMonster: {
        type: Object,
        default: null,
    },
    tileSize: {
        type: Number,
        required: true,
    },
    monsters: {
        type: Array,
        required: true,
    },
});

/* =========================================
BATTLE STATE
========================================= */
const showBattleModal = ref(false);
const battleMonsters = ref([]);
const isAttacking = ref(false);
const playerDamage = ref(null);
const monsterDamages = ref({});
const playerTurn = ref(true);
const battleEnded = ref(false);
const attackingMonsterId = ref(null);
const playerShout = ref("");
const monsterShouts = ref({});
const skillEffect = ref(null);
const skillTargets = ref([]);
const lootDrops = ref([]);
const showLootModal = ref(false);
const isLevelUp = ref(false);
const logs = ref([]);
const emit = defineEmits(["click-monster"]);
/* CRIT CHECK */
function isCritical(critRate) {
    return Math.random() * 100 < critRate;
}

/* EVA CHECK */
function isEvade(evaRate) {
    return Math.random() * 100 < evaRate;
}

/* =========================================
ALIVE MONSTERS
========================================= */
const aliveMonsters = computed(() => {
    return battleMonsters.value.filter((monster) => monster.hp > 0);
});

/* =========================================
OPEN BATTLE
========================================= */
function handleSelectMonster(monster) {
    if (showBattleModal.value) return;

    emit("click-monster", monster);
}

function openBattle(monster) {
    showBattleModal.value = true;

    battleEnded.value = false;

    playerTurn.value = true;

    logs.value = [];

    const monsterCount = Math.random() < 0.5 ? 2 : 3;

    battleMonsters.value = Array.from({ length: monsterCount }, (_, index) => {
        const variance = 0.1;
        const randomHp =
            monster.maxHp +
            Math.floor(
                monster.maxHp * (Math.random() * variance * 2 - variance),
            );

        return {
            id: index + 1,
            name: monster.name,
            hp: randomHp,
            maxHp: randomHp,
            attack: monster.skill.damage,
            eva: randomEva(5, 10),
            skill_name: monster.skill.name,
            element: monster.element,
            drops: monster.drops,
            exp: monster.exp,
            battle_gif: `/monster_sprites/${monster.name}/idle-left.gif`,
            attack_gif: `/monster_sprites/${monster.name}/attack.gif`,
            dead_gif: `/monster_sprites/${monster.name}/dead.gif`,
        };
    });

    logs.value.unshift(`${monster.name} and allies appeared`);
}
defineExpose({
    openBattle,
});
/* =========================================
USE SKILL
========================================= */
function useSkill(skill, selectedMonster = null) {
    if (!playerTurn.value) return;

    if (battleEnded.value) return;

    if (props.player.current_mana < skill.mana) {
        logs.value.unshift("Not enough mana");
        return;
    }

    let targets = [];

    if (skill.targets === 1) {
        if (!selectedMonster) return;

        const target = battleMonsters.value.find(
            (monster) => monster.id === selectedMonster.id,
        );

        if (!target || target.hp <= 0) return;

        targets = [target];
    } else {
        targets = aliveMonsters.value.slice(0, skill.targets);
    }

    skillTargets.value = targets.map((t) => t.id);

    props.player.current_mana -= skill.mana;

    attackAnimation();
    triggerSkillEffect(skill);
    skillShout(skill);

    targets.forEach((monster) => {
        if (isEvade(monster.eva)) {
            monsterDamages.value[monster.id] = "MISS";

            logs.value.unshift(`${monster.name} evaded the attack`);

            setTimeout(() => {
                delete monsterDamages.value[monster.id];
            }, 800);

            return;
        }

        const totalDamage = props.player.attack + skill.damage;

        let damage = randomDamage(totalDamage - 4, totalDamage + 4);

        const crit = isCritical(props.player.crit);

        if (crit) {
            damage = Math.floor(damage * 1.5);
            logs.value.unshift("CRITICAL HIT!");
        }

        // ELEMENT CALCULATION
        const result = applyElementalDamage(
            damage,
            skill.element,
            monster.element,
        );

        damage = result.damage;

        // UI DISPLAY (NOW CORRECT)
        monsterDamages.value[monster.id] = crit ? `${damage} CRIT` : damage;

        if (result.multiplier > 1) {
            logs.value.unshift("It's super effective!");
        } else if (result.multiplier < 1) {
            logs.value.unshift("It's not very effective...");
        }

        setTimeout(() => {
            delete monsterDamages.value[monster.id];
        }, 800);

        monster.hp -= damage;

        if (monster.hp < 0) monster.hp = 0;

        logs.value.unshift(
            `${props.player.name} used ${skill.name} on ${monster.name} for ${damage}`,
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

function applyElementalDamage(damage, attackerElement, defenderElement) {
    const elementChart = {
        fire: {
            earth: 1.2,
            water: 0.9,
            wind: 1.1,
            electric: 1.0,
            fire: 1.0,
        },

        water: {
            fire: 1.2,
            earth: 0.9,
            electric: 0.8,
            wind: 1.1,
            water: 1.0,
        },

        earth: {
            water: 1.2,
            fire: 0.9,
            electric: 1.1,
            wind: 0.9,
            earth: 1.0,
        },

        electric: {
            water: 1.3,
            wind: 1.2,
            earth: 0.8,
            fire: 1.0,
            electric: 1.0,
        },

        wind: {
            earth: 1.2,
            fire: 0.9,
            electric: 0.8,
            water: 1.0,
            wind: 1.0,
        },
    };
    if (
        !attackerElement ||
        !defenderElement ||
        !elementChart[attackerElement]
    ) {
        return {
            damage: Math.floor(damage),
            multiplier: 1,
        };
    }

    const multiplier = elementChart[attackerElement][defenderElement] ?? 1;

    return {
        damage: Math.floor(damage * multiplier),
        multiplier,
    };
}

/* =========================================
SKILL EFFECT
========================================= */
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

function skillShout(skill) {
    playerShout.value = `${skill.name}!!`;

    setTimeout(() => {
        playerShout.value = "";
    }, 1000);
}

function monsterSkillShout(monster) {
    monsterShouts.value[monster.id] = `${monster.skill_name}!!`;

    setTimeout(() => {
        delete monsterShouts.value[monster.id];
    }, 1000);
}

/* =========================================
ATTACK ANIMATION
========================================= */
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
    let delay = 0;

    aliveMonsters.value.forEach((monster) => {
        setTimeout(() => {
            attackingMonsterId.value = monster.id;

            const damage = randomDamage(monster.attack - 2, monster.attack + 2);

            playerDamage.value = damage;

            setTimeout(() => {
                playerDamage.value = null;
            }, 800);

            props.player.current_health -= damage;

            if (props.player.current_health < 0)
                props.player.current_health = 0;

            logs.value.unshift(`${monster.name} attacked for ${damage}`);

            monsterSkillShout(monster);

            setTimeout(() => {
                attackingMonsterId.value = null;
            }, 700);

            checkBattle();

            if (
                monster.id ===
                    aliveMonsters.value[aliveMonsters.value.length - 1]?.id &&
                !battleEnded.value
            ) {
                playerTurn.value = true;
            }
        }, delay);

        delay += 1000;
    });
}

/* =========================================
CHECK BATTLE
========================================= */
async function checkBattle() {
    if (props.player.current_health <= 0) {
        battleEnded.value = true;

        logs.value.unshift("You were defeated");

        setTimeout(() => {
            closeBattle();
        }, 3000);
    }

    if (aliveMonsters.value.length === 0) {
        battleEnded.value = true;

        const drops = generateDrops();

        lootDrops.value = drops;

        if (Object.keys(lootDrops.value).length > 0) {
            showLootModal.value = true;
        }

        try {
            await battleSave();
        } catch (err) {
            console.error("battleSave failed:", err);
        }

        logs.value.unshift("Victory!");

        setTimeout(() => {
            showLootModal.value = false;
            closeBattle();
        }, 3000);
    }
}

function generateDrops() {
    const result = {};

    battleMonsters.value.forEach((monster) => {
        monster.drops.forEach((drop) => {
            const roll = Math.random() * 100;

            if (roll <= drop.chance) {
                if (!result[drop.item]) {
                    result[drop.item] = 0;
                }

                result[drop.item] += 1;
            }
        });
    });

    return result;
}

async function battleSave() {
    try {
        const response = await axios.post("/battle/save", {
            monsters: battleMonsters.value,
            drops: lootDrops.value,
            player: props.player,
        });
        Object.assign(props.player, response.data.player);
        isLevelUp.value = response.data.level_up;

        logs.value.unshift(
            `Level ${response.data.level} (${response.data.exp} EXP)`,
        );
    } catch (error) {
        console.error(error);
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

function randomEva(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + max;
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
                <!-- PLAYER -->
                <div class="player-section">
                    <div class="player-wrapper">
                        <img
                            :src="
                                player.hp <= 0
                                    ? player.dead_gif
                                    : isAttacking
                                      ? player.attack_gif
                                      : player.battle_gif
                            "
                            class="player-sprite"
                        />

                        <div v-if="playerShout" class="shout-text player-shout">
                            {{ playerShout }}
                        </div>

                        <div
                            v-if="playerDamage"
                            class="damage-text player-damage"
                        >
                            -{{ playerDamage }}
                        </div>

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

                <!-- MONSTERS -->
                <div class="monsters-section">
                    <div
                        v-if="skillEffect"
                        class="skill-effect"
                        :class="skillEffect"
                    ></div>
                    <div
                        v-for="monster in battleMonsters"
                        :key="monster.id"
                        class="monster-card"
                    >
                        <div
                            v-if="monsterShouts[monster.id]"
                            class="shout-text monster-shout"
                        >
                            {{ monsterShouts[monster.id] }}
                        </div>
                        <img
                            :src="
                                monster.hp <= 0
                                    ? monster.dead_gif
                                    : attackingMonsterId === monster.id
                                      ? monster.attack_gif
                                      : monster.battle_gif
                            "
                            class="monster-sprite"
                            :class="{
                                'monster-hurt': monsterDamages[monster.id],
                            }"
                        />

                        <div
                            v-if="monsterDamages[monster.id]"
                            class="damage-text monster-damage"
                        >
                            -{{ monsterDamages[monster.id] }}
                        </div>

                        <div class="monster-ui">
                            <!-- NAME -->
                            <p class="monster-name">
                                {{ monster.name }}
                            </p>

                            <!-- HP BAR -->
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
                <div
                    v-for="skill in player.skills"
                    :key="skill.id"
                    class="skill-card"
                >
                    <!-- SKILL ICON CENTER -->
                    <div class="skill-icon-wrap" @click="useSkill(skill)">
                        <img
                            :src="skill.icon || '/icons/default-skill.png'"
                            class="skill-icon"
                        />
                    </div>

                    <!-- SKILL NAME -->
                    <p class="skill-name">
                        {{ skill.name }}
                    </p>

                    <!-- TARGETS -->
                    <div class="target-buttons">
                        <template v-if="skill.targets === 1">
                            <button
                                v-for="(monster, index) in aliveMonsters"
                                :key="monster.id"
                                class="target-btn skill-btn"
                                :disabled="!playerTurn || battleEnded"
                                @click="useSkill(skill, monster)"
                            >
                                Cast
                            </button>
                        </template>

                        <template v-else>
                            <button
                                class="target-btn skill-btn"
                                :disabled="!playerTurn || battleEnded"
                                @click="useSkill(skill)"
                            >
                                Cast
                            </button>
                        </template>
                    </div>
                </div>
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
    <LootDrops :lootDrops="lootDrops" v-if="showLootModal" />
    <Monster
        :monsters="monsters"
        :tileSize="tileSize"
        @select-monster="handleSelectMonster"
    />
    <LevelUp
        :levelUp="isLevelUp"
        :level="player.current_level"
        @cleared="isLevelUp = false"
    />
</template>

<style scoped>
/* =========================================
OVERLAY
========================================= */
.pve-modal-wrapper {
    position: absolute;
    inset: 0;

    /* LESS DARK / MORE TRANSPARENT */
    background: rgba(0, 0, 0, 0.35);

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

    gap: 10px;
}

/* SMALLER PLAYER */
.player-sprite {
    width: 70px;
    height: 70px;

    object-fit: contain;

    image-rendering: pixelated;

    filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.08));
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

    top: 270px;
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
</style>
