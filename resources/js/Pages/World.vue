<template>
    <div class="game-wrapper">
        <div class="hud-top" style="background-color: rgba(0, 0, 0, 0.5)">
            <!-- HP -->
            <div class="bar hp">
                <div
                    class="fill"
                    :style="{ width: (stats.hp / stats.maxHp) * 100 + '%' }"
                ></div>
                <span>HP {{ stats.hp }} / {{ stats.maxHp }}</span>
            </div>

            <!-- MP -->
            <div class="bar mp">
                <div
                    class="fill"
                    :style="{ width: (stats.mp / stats.maxMp) * 100 + '%' }"
                ></div>
                <span>MP {{ stats.mp }} / {{ stats.maxMp }}</span>
            </div>

            <!-- EXP -->
            <div class="bar exp">
                <div
                    class="fill"
                    :style="{ width: (stats.exp / stats.maxExp) * 100 + '%' }"
                ></div>
                <span>EXP {{ stats.exp }}%</span>
            </div>
        </div>
        <div
            class="game-map"
            @click="handleMapClick"
            @mousemove="handleMouseMove"
            :style="{
                width: mapWidth * tileSize + 'px',
                height: mapHeight * tileSize + 'px',
                backgroundImage: `url('/maps/town1.png')`,
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

            <!-- MONSTERS -->
            <div
                v-for="monster in monsters"
                :key="monster.id"
                class="monster"
                :style="{
                    width: tileSize + 'px',
                    height: tileSize + 'px',
                    transform: `translate(${monster.renderX}px, ${monster.renderY}px)`,
                }"
            >
                <!-- <img src="/sprites/monster.png" class="sprite" /> -->😈
            </div>

            <!-- PLAYER -->
            <div
                class="player"
                :style="{
                    width: tileSize + 'px',
                    height: tileSize + 'px',
                    transform: `translate(${player.renderX}px, ${player.renderY}px)`,
                }"
            >
                <!-- <img src="/sprites/player.png" class="sprite" /> 🐉 -->
                <!-- <img
                    :src="`/sprites/player-${player.direction}.gif`"
                    class="sprite"
                /> -->
                🐉
            </div>
        </div>
        <div class="hud-bottom">
            <div class="skill">1</div>
            <div class="skill">2</div>
            <div class="skill">3</div>
            <div class="skill">4</div>
            <div class="skill">5</div>
        </div>
    </div>
</template>

<script setup>
import { reactive, computed, onMounted } from "vue";

const tileSize = 64;
const moveQueue = reactive([]);
const hoverTile = reactive({
    x: 0,
    y: 0,
    visible: false,
    blocked: false,
});

const stats = reactive({
    hp: 80,
    maxHp: 100,
    mp: 40,
    maxMp: 100,
    exp: 30,
    maxExp: 100,
});
const hoverBlocked = reactive({
    value: false,
});
/*
|--------------------------------------------------------------------------
| MAP COLLISION
|--------------------------------------------------------------------------
| 0 = walkable
| 1 = blocked
|--------------------------------------------------------------------------
*/

const map = [
    [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
    [1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
    [1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 1],
    [1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1],
    [1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1],
    [1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1],
    [1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1],
    [1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1],
    [1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1],
    [1, 1, 1, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
    [1, 1, 1, 1, 1, 0, 0, 0, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
    [1, 1, 1, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
    [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
];

const mapHeight = map.length;
const mapWidth = map[0].length;

const flatMap = computed(() => map.flat());

const player = reactive({
    x: 10,
    y: 2,
    renderX: 10 * tileSize,
    renderY: 2 * tileSize,
    direction: "down",
    moving: false,
});

const monsters = reactive([
    {
        id: 1,
        x: 7,
        y: 4,
        renderX: 7 * tileSize,
        renderY: 4 * tileSize,
        dir: 1,
    },
    {
        id: 2,
        x: 9,
        y: 6,
        renderX: 9 * tileSize,
        renderY: 6 * tileSize,
        dir: -1,
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

    player.x = next.x;
    player.y = next.y;

    player.moving = true;

    smoothMove(player, next.x * tileSize, next.y * tileSize, () => {
        player.moving = false;
        processQueue(); // continue walking
    });
}

function isBlocked(x, y) {
    if (x < 0 || y < 0) return true;

    if (x >= mapWidth || y >= mapHeight) return true;

    return map[y][x] === 1;
}

function smoothMove(entity, targetX, targetY, callback = null) {
    const speed = 6;

    function animate() {
        const dx = targetX - entity.renderX;
        const dy = targetY - entity.renderY;

        if (Math.abs(dx) < 1 && Math.abs(dy) < 1) {
            entity.renderX = targetX;
            entity.renderY = targetY;

            callback?.();

            return;
        }

        entity.renderX += dx / speed;
        entity.renderY += dy / speed;

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
    setInterval(() => {
        monsters.forEach((monster) => {
            const directions = [
                { dx: 0, dy: -1 }, // up
                { dx: 0, dy: 1 }, // down
                { dx: -1, dy: 0 }, // left
                { dx: 1, dy: 0 }, // right
            ];

            // pick random direction
            const move =
                directions[Math.floor(Math.random() * directions.length)];

            const nextX = monster.x + move.dx;
            const nextY = monster.y + move.dy;

            // check collision
            if (!isBlocked(nextX, nextY)) {
                monster.x = nextX;
                monster.y = nextY;

                smoothMove(monster, monster.x * tileSize, monster.y * tileSize);
            }
        });
    }, 3000);
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

.player,
.monster {
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10;
}

.sprite {
    width: 100%;
    height: 100%;
    object-fit: contain;

    image-rendering: pixelated;
}

.controls {
    color: white;
    text-align: center;
}

.hud-top {
    position: fixed;
    top: 50px;
    left: 20%;
    transform: translateX(-50%);

    width: min(300px, 90vw);

    display: flex;
    flex-direction: column;
    gap: 6px;

    z-index: 999;
}

.bar {
    position: relative;
    height: 10px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 6px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.bar span {
    position: absolute;
    width: 100%;
    text-align: center;
    font-size: 12px;
    color: white;
    line-height: 10px;
    z-index: 2;
}

.fill {
    height: 100%;
    width: 0%;
    transition: width 0.2s linear;
}

.hp .fill {
    background: linear-gradient(to right, #ff3b3b, #ff6b6b);
}

.mp .fill {
    background: linear-gradient(to right, #3b82f6, #60a5fa);
}

.exp .fill {
    background: linear-gradient(to right, #facc15, #fde047);
}

/* SKILL BAR */
.hud-bottom {
    position: fixed;
    bottom: 50px;
    left: 50%;
    transform: translateX(-50%);

    display: flex;
    gap: 10px;

    z-index: 999;
}

.skill {
    width: 50px;
    height: 50px;

    background: rgba(0, 0, 0, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;

    display: flex;
    justify-content: center;
    align-items: center;

    color: white;
    font-weight: bold;

    cursor: pointer;
}

.skill:hover {
    background: rgba(255, 255, 255, 0.1);
}
</style>
