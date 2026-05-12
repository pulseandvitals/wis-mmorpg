<template>
    <Head title="Elfaria Online" />
    <div class="game-wrapper">
        <div
            class="game-map"
            @click="handleMapClick"
            @mousemove="handleMouseMove"
            :style="{
                width: mapWidth * tileSize + 'px',
                height: mapHeight * tileSize + 'px',
                backgroundImage: `url('/maps/${current_map.name}.png')`,
                cursor: hoverBlocked.value ? 'not-allowed' : 'pointer',
            }"
        >
            <!-- COLLISION DEBUG (OPTIONAL) -->
            <div
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
            />

            <!-- PLAYER -->
            <Player :player="player" :tileSize="tileSize" />

            <!-- HUD COMPONENTS -->
            <Menu />
            <PlayerStat :playerData="playerData.data" />
            <TownSquareNPC v-if="current_map.name === 'Town Square'" />
            <PlayerSkill />
            <WorldChat />
            <PvE
                :playerData="playerData.data"
                :monsters="monsters"
                :tileSize="tileSize"
            />
        </div>
    </div>
</template>

<script setup>
import { reactive, computed, onMounted, ref } from "vue";
import PlayerStat from "./GameComponents/PlayerStat.vue";
import PlayerSkill from "./GameComponents/PlayerSkill.vue";
import WorldChat from "./GameComponents/WorldChat.vue";
import Player from "./GameComponents/Player.vue";
import PvE from "./GameComponents/Battle.vue/PvE.vue";
import Menu from "./GameComponents/Menu.vue";
import TownSquareNPC from "./GameComponents/Npc/TownSquareNPC.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    playerData: Object,
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

const flatMap = computed(() => map.flat());

const player = reactive({
    x: props.playerData.data.x,
    y: props.playerData.data.y,
    className: props.playerData.data.class_type,
    renderX: props.playerData.data.x * tileSize,
    renderY: props.playerData.data.y * tileSize,
    name: props.playerData.data.name,
    direction: "down",
    moving: false,
    hp: props.playerData.data.current_health,
    maxHp: props.playerData.data.max_health,
    mp: props.playerData.data.current_mana,
    maxMp: props.playerData.data.max_mana,
});

const monsters = reactive([
    {
        id: 1,
        name: "Orc Warrior",
        hp: 50,
        maxHp: 50,
        attack: 10,
        x: 7,
        y: 4,
        renderX: 7 * tileSize,
        renderY: 4 * tileSize,
        dir: 1,

        // NEW
        moving: false,
        direction: "down",
    },
    {
        id: 2,
        name: "Orc Zombie",
        hp: 50,
        maxHp: 50,
        attack: 10,
        x: 9,
        y: 6,
        renderX: 9 * tileSize,
        renderY: 6 * tileSize,
        dir: -1,

        // NEW
        moving: false,
        direction: "down",
    },
]);

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

let nextMoveTime = 0;

function moveMonsters() {
    function loop(timestamp) {
        if (nextMoveTime === 0) {
            nextMoveTime = timestamp + 3000;
        }

        if (timestamp >= nextMoveTime) {
            monsters.forEach((monster) => {
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
            });

            // reset timer cleanly (no backlog buildup)
            nextMoveTime = timestamp + 3000;
        }

        requestAnimationFrame(loop);
    }

    requestAnimationFrame(loop);
}
onMounted(() => {
    window.addEventListener("keydown", handleKey);

    moveMonsters();
});
</script>

<style scoped>
.game-wrapper {
    min-height: 100vh;
    background: #111827;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    gap: 20px;
    overflow: hidden;

    font-family: "Nova Square", sans-serif;

    font-smooth: never;
    -webkit-font-smoothing: none;
    text-rendering: geometricPrecision;
    image-rendering: pixelated;
    letter-spacing: 1px;
    font-size: 14px;
}
.tile {
    position: absolute;
    box-sizing: border-box;
}
.game-map {
    position: relative;

    width: calc(24 * 64px);
    height: calc(13 * 64px);

    background-image: url("/maps/town1.png");
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
