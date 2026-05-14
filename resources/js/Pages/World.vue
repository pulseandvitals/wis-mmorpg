<script setup>
import { reactive, computed, onMounted, ref, watch } from "vue";
import PlayerStat from "./GameComponents/PlayerStat.vue";
import PlayerSkill from "./GameComponents/PlayerSkill.vue";
import WorldChat from "./GameComponents/WorldChat.vue";
import Player from "./GameComponents/Player.vue";
import PvE from "./GameComponents/Battle.vue/PvE.vue";
import Menu from "./GameComponents/Menu.vue";
import TownSquareNPC from "./GameComponents/Npc/TownSquareNPC.vue";
import { Head } from "@inertiajs/vue3";
import GameLayout from "@/Layouts/GameLayout.vue";

const props = defineProps({
    playerData: Object,
    playerSkills: Object,
    classSkills: Object,
    all_maps: Object,
    monsters: Object,
    current_map: Object,
    map_tiles: Array,
});

const tileSize = 64;
const moveQueue = reactive([]);
const hoverTile = reactive({
    x: 0,
    y: 0,
    visible: false,
    blocked: false,
});
const hoverBlocked = reactive({
    value: false,
});
const map = props.map_tiles;
const mapHeight = map.length;
const mapWidth = map[0].length;
const monsters = reactive([]);
const flatMap = computed(() => map.flat());

const player = reactive({
    x: props.playerData.data.x,
    y: props.playerData.data.y,
    className: props.playerData.data.class_type,
    renderX: props.playerData.data.x * tileSize,
    renderY: props.playerData.data.y * tileSize,
    direction: "down",
    moving: false,

    name: props.playerData.data.name,
    class_type: props.playerData.data.class_type,
    current_health: props.playerData.data.current_health,
    max_health: props.playerData.data.max_health,
    current_mana: props.playerData.data.current_mana,
    max_mana: props.playerData.data.max_mana,
    current_level: props.playerData.data.current_level,
    current_experience: props.playerData.data.current_experience,
    attack: props.playerData.data.total_attack,
    def: props.playerData.data.total_defense,
    crit: props.playerData.data.total_critical_percentage,
    eva: props.playerData.data.total_evasion_percentage,

    battle_gif: `/sprites/${props.playerData.data.class_type}/idle-right.gif`,
    attack_gif: `/sprites/${props.playerData.data.class_type}/attack.gif`,
    dead_gif: `/sprites/${props.playerData.data.class_type}/dead.gif`,

    skills: props.playerSkills.data.map((skill) => ({
        id: skill.id,
        name: skill.name,
        description: skill.description,
        damage: skill.damage,
        mana: skill.mana_cost,
        targets: skill.target,
        element: skill.element,
        icon: skill.icon_path,
    })),
});
function loadMonsters() {
    const dbMonsters = Array.isArray(props.monsters)
        ? props.monsters
        : Object.values(props.monsters || {});

    dbMonsters.forEach((monster) => {
        for (let i = 0; i < 4; i++) {
            const x = Math.floor(Math.random() * 20);
            const y = Math.floor(Math.random() * 15);

            monsters.push({
                ...monster,
                hp: monster.max_hp,
                maxHp: monster.max_hp,
                skill:
                    typeof monster.skill === "string"
                        ? JSON.parse(monster.skill)
                        : monster.skill,

                drops:
                    typeof monster.drops === "string"
                        ? JSON.parse(monster.drops)
                        : monster.drops,

                x,
                y,
                renderX: x * tileSize,
                renderY: y * tileSize,
                moving: false,
                direction: "down",
            });
        }
    });
}

function handleMouseMove(e) {
    const rect = e.currentTarget.getBoundingClientRect();

    const x = Math.floor((e.clientX - rect.left) / tileSize);
    const y = Math.floor((e.clientY - rect.top) / tileSize);

    hoverBlocked.value = isBlocked(x, y);
    hoverTile.visible = true;
    hoverTile.x = x;
    hoverTile.y = y;
    hoverTile.blocked = isBlocked(x, y);
}
function handleMapClick(e) {
    const rect = e.currentTarget.getBoundingClientRect();

    const targetX = Math.floor((e.clientX - rect.left) / tileSize);
    const targetY = Math.floor((e.clientY - rect.top) / tileSize);

    if (isBlocked(targetX, targetY)) return;

    // clear old path
    moveQueue.length = 0;

    let x = player.x;
    let y = player.y;

    // build simple step path (grid straight-line movement)
    while (x !== targetX || y !== targetY) {
        if (x < targetX) x++;
        else if (x > targetX) x--;
        else if (y < targetY) y++;
        else if (y > targetY) y--;

        moveQueue.push({ x, y });
    }

    processQueue();
}

function processQueue() {
    if (player.moving) return;
    if (moveQueue.length === 0) return;

    const next = moveQueue.shift();

    if (isBlocked(next.x, next.y)) {
        moveQueue.length = 0;
        return;
    }

    const dx = next.x - player.x;
    const dy = next.y - player.y;

    if (dx > 0) player.direction = "right";
    if (dx < 0) player.direction = "left";
    if (dy > 0) player.direction = "down";
    if (dy < 0) player.direction = "up";

    player.x = next.x;
    player.y = next.y;

    player.moving = true;

    smoothMove(player, next.x * tileSize, next.y * tileSize, () => {
        player.moving = false;
        processQueue();
    });
}

function isBlocked(x, y) {
    if (x < 0 || y < 0) return true;

    if (x >= mapWidth || y >= mapHeight) return true;

    return map[y][x] === 1;
}

function smoothMove(entity, targetX, targetY, callback = null) {
    const speed = 2.5; // pixels per frame (lower = smoother, slower)
    if (document.hidden) {
        requestAnimationFrame(animate);
        return;
    }
    function animate() {
        const dx = targetX - entity.renderX;
        const dy = targetY - entity.renderY;

        const distance = Math.sqrt(dx * dx + dy * dy);

        // stop condition
        if (distance <= speed) {
            entity.renderX = targetX;
            entity.renderY = targetY;

            callback?.();
            return;
        }

        // normalize direction (prevents diagonal “skating” speed boost)
        const nx = dx / distance;
        const ny = dy / distance;

        entity.renderX += nx * speed;
        entity.renderY += ny * speed;

        requestAnimationFrame(animate);
    }

    animate();
}

function movePlayer(dx, dy) {
    if (player.moving) return;

    const nextX = player.x + dx;
    const nextY = player.y + dy;

    if (isBlocked(nextX, nextY)) return;

    player.x = nextX;
    player.y = nextY;

    if (dx === 1) player.direction = "right";
    if (dx === -1) player.direction = "left";
    if (dy === 1) player.direction = "down";
    if (dy === -1) player.direction = "up";

    player.moving = true;

    smoothMove(player, nextX * tileSize, nextY * tileSize, () => {
        player.moving = false;
    });
}

function handleKey(e) {
    switch (e.key) {
        case "ArrowUp":
        case "w":
            movePlayer(0, -1);
            break;

        case "ArrowDown":
        case "s":
            movePlayer(0, 1);
            break;

        case "ArrowLeft":
        case "a":
            movePlayer(-1, 0);
            break;

        case "ArrowRight":
        case "d":
            movePlayer(1, 0);
            break;
    }
}

function moveMonsters() {
    function loop(timestamp) {
        monsters.forEach((monster) => {
            // create unique timer for each monster
            if (!monster.nextMoveTime) {
                monster.nextMoveTime = timestamp + 1000 + Math.random() * 3000;
            }

            // only move THIS monster when its timer is ready
            if (timestamp >= monster.nextMoveTime && !monster.moving) {
                const directions = [
                    { dx: 0, dy: -1 },
                    { dx: 0, dy: 1 },
                    { dx: -1, dy: 0 },
                    { dx: 1, dy: 0 },
                ];

                const move =
                    directions[Math.floor(Math.random() * directions.length)];

                const nextX = monster.x + move.dx;
                const nextY = monster.y + move.dy;

                if (!isBlocked(nextX, nextY)) {
                    if (move.dx > 0) monster.direction = "right";
                    if (move.dx < 0) monster.direction = "left";
                    if (move.dy > 0) monster.direction = "down";
                    if (move.dy < 0) monster.direction = "up";

                    monster.moving = true;

                    monster.x = nextX;
                    monster.y = nextY;

                    smoothMove(
                        monster,
                        monster.x * tileSize,
                        monster.y * tileSize,
                        () => {
                            monster.moving = false;
                        },
                    );
                }

                // random next move time PER monster
                monster.nextMoveTime = timestamp + 1000 + Math.random() * 3000;
            }
        });

        requestAnimationFrame(loop);
    }

    requestAnimationFrame(loop);
}
onMounted(() => {
    window.addEventListener("keydown", handleKey);
    moveMonsters();
});
watch(
    () => props.monsters,
    () => {
        loadMonsters();
    },
    { immediate: true },
);
</script>
<template>
    <Head title="Wis Online" />
    <GameLayout>
        <div
            class="game-map"
            @click.self="handleMapClick"
            @mousemove="handleMouseMove"
            :style="{
                width: mapWidth * tileSize + 'px',
                height: mapHeight * tileSize + 'px',
                backgroundImage: `url('/maps/${current_map.name}.png')`,
                cursor: hoverBlocked.value ? 'not-allowed' : 'pointer',
            }"
        >
            <!-- COLLISION DEBUG (OPTIONAL) -->
            <!-- <div
                v-for="(tile, index) in flatMap"
                :key="index"
                class="tile"
                :class="{
                    wall: tile === 1,
                }"
                :style="{
                    width: tileSize + 'px',
                    height: tileSize + 'px',
                    position: 'absolute',
                    left: (index % mapWidth) * tileSize + 'px',
                    top: Math.floor(index / mapWidth) * tileSize + 'px',
                }"
            /> -->

            <!-- PLAYER -->
            <Player :player="player" :tileSize="tileSize" />

            <!-- HUD COMPONENTS -->
            <Menu :classSkills="classSkills.data" :all_maps="all_maps.data" />
            <PlayerStat :player="player" />
            <TownSquareNPC
                v-if="current_map.name === 'Town Square'"
                :all_maps="all_maps.data"
                :player="player"
            />
            <PlayerSkill :playerSkills="playerSkills.data" />
            <WorldChat />
            <PvE :player="player" :monsters="monsters" :tileSize="tileSize" />
        </div>
    </GameLayout>
</template>
<style scoped>
.tile {
    position: absolute;
    box-sizing: border-box;
}
.game-map {
    position: relative;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;

    border: 4px solid #374151;
    overflow: hidden;

    image-rendering: pixelated;
    cursor: pointer;
}
.game-map.blocked {
    cursor: not-allowed;
}

/* COLLISION DEBUG */
.tile {
    box-sizing: border-box;
    border: 1px solid rgba(255, 255, 255, 0.03);
}

.wall {
    background: rgba(255, 0, 0, 0.2);
}
</style>
