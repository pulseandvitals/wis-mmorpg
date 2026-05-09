<script setup>
import { ref, computed } from "vue";

const player = ref({
    name: "Knight",
    hp: 120,
    maxHp: 120,
    attack: 18,

    // PLAYER GIF
    battle_gif: "/sprites/player_idle.gif",
    attack_gif: "/sprites/player_attack.gif",
    dead_gif: "/sprites/player_dead.gif",
});

const worldMonsters = ref([
    {
        id: 1,
        name: "Goblin",
        hp: 50,
        maxHp: 50,
        attack: 8,
        battle_gif: "/sprites/goblin.gif",
        attack_gif: "/sprites/goblin_attack.gif",
        dead_gif: "/sprites/goblin_dead.gif",
    },
    {
        id: 2,
        name: "Wolf",
        hp: 60,
        maxHp: 60,
        attack: 10,
        gif: "/sprites/wolf.gif",
    },
    {
        id: 3,
        name: "Skeleton",
        hp: 70,
        maxHp: 70,
        attack: 12,
        gif: "/sprites/skeleton.gif",
    },
    {
        id: 4,
        name: "Orc",
        hp: 85,
        maxHp: 85,
        attack: 14,
        gif: "/sprites/orc.gif",
    },
    {
        id: 5,
        name: "Slime",
        hp: 35,
        maxHp: 35,
        attack: 5,
        gif: "/sprites/slime.gif",
    },
    {
        id: 6,
        name: "Dragon",
        hp: 150,
        maxHp: 150,
        attack: 20,
        gif: "/sprites/dragon.gif",
    },
]);

const battleMonsters = ref([]);

const showBattleModal = ref(false);
const playerTurn = ref(true);
const battleEnded = ref(false);

const logs = ref([]);

const aliveMonsters = computed(() => {
    return battleMonsters.value.filter((monster) => monster.hp > 0);
});

function openBattle(monster) {
    showBattleModal.value = true;
    battleEnded.value = false;
    playerTurn.value = true;
    logs.value = [];

    player.value.hp = player.value.maxHp;

    const monsterCount = Math.random() < 0.5 ? 2 : 3;

    battleMonsters.value = Array.from({ length: monsterCount }, (_, index) => {
        const randomHp = monster.maxHp + Math.floor(Math.random() * 20);
        const randomAttack = monster.attack + Math.floor(Math.random() * 5);

        return {
            id: index + 1,
            name: monster.name,
            hp: randomHp,
            maxHp: randomHp,
            attack: randomAttack,
            gif: monster.gif,
        };
    });

    logs.value.unshift(`${monster.name} and allies appeared`);
}

function attackMonster(monster) {
    if (!playerTurn.value) return;
    if (battleEnded.value) return;
    if (monster.hp <= 0) return;

    const damage = randomDamage(
        player.value.attack - 4,
        player.value.attack + 5,
    );

    monster.hp -= damage;

    if (monster.hp < 0) {
        monster.hp = 0;
    }

    logs.value.unshift(
        `${player.value.name} attacked ${monster.name} for ${damage}`,
    );

    if (monster.hp <= 0) {
        logs.value.unshift(`${monster.name} defeated`);
    }

    checkBattle();

    if (!battleEnded.value) {
        playerTurn.value = false;

        setTimeout(() => {
            monsterTurn();
        }, 1200);
    }
}

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

function closeBattle() {
    showBattleModal.value = false;
}

function randomDamage(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
</script>

<template>
    <!-- BATTLE MODAL - Inside Game Map -->
    <div v-if="showBattleModal" class="pve-modal">
        <!-- CLOSE BUTTON -->
        <button @click="closeBattle" class="close-btn">✕</button>

        <!-- TITLE -->
        <h2 class="battle-title">Battle</h2>

        <!-- CONTENT -->
        <div class="battle-content">
            <!-- PLAYER SIDE (LEFT) -->
            <div class="player-section">
                <img :src="player.battle_gif" class="player-sprite" />
                <div class="player-info">
                    <p class="player-name">{{ player.name }}</p>
                    <div class="hp-bar-large">
                        <div
                            class="hp-fill-green"
                            :style="{
                                width: (player.hp / player.maxHp) * 100 + '%',
                            }"
                        />
                    </div>
                    <p class="hp-text-large">
                        {{ player.hp }}/{{ player.maxHp
                        }}<span class="label"> HP</span>
                    </p>
                </div>
            </div>

            <!-- VS -->
            <div class="vs-separator">VS</div>

            <!-- MONSTERS SIDE (RIGHT) -->
            <div class="monsters-section">
                <div
                    v-for="monster in battleMonsters"
                    :key="monster.id"
                    class="monster-card"
                >
                    <img :src="monster.gif" class="monster-sprite" />
                    <p class="monster-name">{{ monster.name }}</p>
                    <div class="hp-bar-small">
                        <div
                            class="hp-fill-red"
                            :style="{
                                width: (monster.hp / monster.maxHp) * 100 + '%',
                            }"
                        />
                    </div>
                    <p class="hp-text-small">
                        {{ monster.hp }}/{{ monster.maxHp }}
                    </p>
                    <button
                        @click="attackMonster(monster)"
                        :disabled="
                            monster.hp <= 0 || !playerTurn || battleEnded
                        "
                        class="attack-btn"
                    >
                        Attack
                    </button>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="battle-footer">
            <p class="log-text">{{ logs[0] || "Ready to battle!" }}</p>
            <p class="turn-text">
                {{ playerTurn ? "🗡️ Your Turn" : "⚔️ Enemy Turn" }}
            </p>
        </div>
    </div>

    <!-- MONSTER SELECT SCREEN -->
    <div v-if="!showBattleModal" class="monster-select">
        <div
            v-for="monster in worldMonsters"
            :key="monster.id"
            class="select-card"
            @click="openBattle(monster)"
        >
            <img :src="monster.gif" class="select-sprite" />
            <p class="select-name">{{ monster.name }}</p>
        </div>
    </div>
</template>

<style scoped>
/* MONSTER SELECT SCREEN */
.monster-select {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(2, 1fr);
    gap: 12px;
    padding: 20px;
    z-index: 5000;
    border-radius: 8px;
    overflow: hidden;
}

.select-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.6);
    border: 2px solid #fbbf24;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.select-card:hover {
    background: rgba(0, 0, 0, 0.4);
    border-color: #fcd34d;
    transform: scale(1.05);
}

.select-sprite {
    width: 80px;
    height: 80px;
    object-fit: contain;
    margin-bottom: 8px;
}

.select-name {
    color: #facc15;
    font-size: 8px;
    font-weight: bold;
    font-family: "Press Start 2P", cursive;
}

/* BATTLE MODAL */
.pve-modal {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.85);
    border: 3px solid #fbbf24;
    border-radius: 8px;
    padding: 20px;
    z-index: 5000;
    display: flex;
    flex-direction: column;
    gap: 15px;
    font-family: "Press Start 2P", cursive;
    overflow: hidden;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 28px;
    height: 28px;
    background: #dc2626;
    border: 2px solid #991b1b;
    border-radius: 4px;
    color: white;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 5001;
    transition: background 0.2s;
}

.close-btn:hover {
    background: #991b1b;
}

.battle-title {
    color: #fbbf24;
    font-size: 12px;
    text-align: center;
    margin: 0;
    padding-top: 5px;
}

.battle-content {
    flex: 1;
    display: flex;
    gap: 20px;
    align-items: center;
    justify-content: space-around;
    overflow: hidden;
}

/* PLAYER SECTION */
.player-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    flex: 0 0 auto;
}

.player-sprite {
    width: 140px;
    height: 140px;
    object-fit: contain;
}

.player-info {
    text-align: center;
    width: 130px;
}

.player-name {
    color: #fbbf24;
    font-size: 8px;
    margin: 0 0 4px 0;
    font-weight: bold;
}

.hp-bar-large {
    width: 100%;
    height: 14px;
    background: rgba(0, 0, 0, 0.8);
    border: 2px solid #16a34a;
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 2px;
    box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.8);
}

.hp-fill-green {
    height: 100%;
    background: linear-gradient(to right, #22c55e, #16a34a);
    transition: width 0.3s ease;
}

.hp-text-large {
    color: #86efac;
    font-size: 7px;
    margin: 0;
    font-weight: bold;
}

.hp-text-large .label {
    margin-left: 2px;
}

/* VS SEPARATOR */
.vs-separator {
    color: #ef4444;
    font-size: 12px;
    font-weight: bold;
    opacity: 0.7;
}

/* MONSTERS SECTION */
.monsters-section {
    display: flex;
    flex-direction: column;
    gap: 12px;
    flex: 1;
    overflow-y: auto;
}

.monsters-section::-webkit-scrollbar {
    width: 6px;
}

.monsters-section::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
}

.monster-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    padding: 8px;
}

.monster-sprite {
    width: 90px;
    height: 90px;
    object-fit: contain;
}

.monster-name {
    color: #ef4444;
    font-size: 7px;
    margin: 0;
    font-weight: bold;
}

.hp-bar-small {
    width: 80px;
    height: 8px;
    background: rgba(0, 0, 0, 0.8);
    border: 1px solid #991b1b;
    border-radius: 2px;
    overflow: hidden;
    box-shadow: inset 0 0 3px rgba(0, 0, 0, 0.8);
}

.hp-fill-red {
    height: 100%;
    background: linear-gradient(to right, #ef4444, #991b1b);
    transition: width 0.3s ease;
}

.hp-text-small {
    color: #fca5a5;
    font-size: 6px;
    margin: 0;
    font-weight: bold;
}

.attack-btn {
    padding: 4px 8px;
    background: #dc2626;
    color: white;
    border: 1px solid #991b1b;
    border-radius: 3px;
    cursor: pointer;
    font-size: 6px;
    font-weight: bold;
    font-family: inherit;
    transition: background 0.2s;
    margin-top: 2px;
}

.attack-btn:hover:not(:disabled) {
    background: #991b1b;
}

.attack-btn:disabled {
    background: #4b5563;
    cursor: not-allowed;
    opacity: 0.5;
}

/* FOOTER */
.battle-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 2px solid #666;
    padding-top: 8px;
    gap: 10px;
    min-height: 24px;
}

.log-text {
    color: #d1d5db;
    font-size: 6px;
    margin: 0;
    flex: 1;
    word-break: break-word;
    line-height: 1.2;
}

.turn-text {
    color: #facc15;
    font-size: 7px;
    margin: 0;
    font-weight: bold;
    white-space: nowrap;
}
</style>
