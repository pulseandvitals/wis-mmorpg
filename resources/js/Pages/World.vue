<script setup>
import { reactive, computed, onMounted, ref, watch } from "vue";
import PlayerStat from "./GameComponents/PlayerStat.vue";
import PlayerSkill from "./GameComponents/PlayerSkill.vue";
import WorldChat from "./GameComponents/WorldChat.vue";
import Player from "./GameComponents/Player.vue";
import PvE from "./GameComponents/Battle/PvE.vue";
import PvP from "./GameComponents/Battle/PvP.vue";
import Menu from "./GameComponents/Menu.vue";
import TownSquareNPC from "./GameComponents/Npc/TownSquareNPC.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import GameLayout from "@/Layouts/GameLayout.vue";
import Players from "./GameComponents/Players.vue";
import Portal from "./GameComponents/Portal.vue";
import { pushAlert } from "@/Stores/GlobalAlert";
import PartyList from "./GameComponents/Npc/PartyList.vue";
import ActiveBuffs from "./GameComponents/ActiveBuffs.vue";
import Tutorial from "./GameComponents/Tutorial.vue";
import Gacha from "./GameComponents/Gacha.vue";
import Emoji from "./GameComponents/Emoji.vue";

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
const selectedMonster = ref(null);
const pveRef = ref(null);
const pvpRef = ref(null);
const party = ref([]);
const activeEmoji = ref(null);
const playersEmoji = ref(null);
const flatMap = computed(() => map.flat());
const spriteFolder = props.playerData.data.wing
    ? `${props.playerData.data.class_type} ${props.playerData.data.wing?.gear?.name}`
    : props.playerData.data.class_type;

const player = reactive({
    x: props.playerData.data.x,
    y: props.playerData.data.y,
    className: props.playerData.data.class_type,
    renderX: props.playerData.data.x * tileSize,
    renderY: props.playerData.data.y * tileSize,
    direction: "down",
    moving: false,

    id: props.playerData.data.id,
    name: props.playerData.data.name,
    guild: props.playerData.data.guild,
    class_type: props.playerData.data.class_type,
    is_exp_potion_active: props.playerData.data.is_exp_potion_active,
    current_health: props.playerData.data.current_health,
    max_health: props.playerData.data.max_health,
    current_mana: props.playerData.data.current_mana,
    max_mana: props.playerData.data.max_mana,
    current_level: props.playerData.data.current_level,
    current_experience: props.playerData.data.current_experience,
    experience_percentage: props.playerData.data.experience_percentage,
    attack: props.playerData.data.total_attack,
    def: props.playerData.data.total_defense,
    crit: props.playerData.data.total_critical_percentage,
    eva: props.playerData.data.total_evasion_percentage,

    sprite: spriteFolder,
    battle_gif: `/sprites/${spriteFolder}/idle-right.gif`,
    attack_gif: `/sprites/${spriteFolder}/attack.gif`,
    dead_gif: `/sprites/${spriteFolder}/dead.gif`,

    active_buff_effects: props.playerData.data.active_buff_effects,

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
const updatePlayer = (newUpdate) => {
    props.playerData.data = newUpdate;
};
const updateParty = (newParty) => {
    party.value = newParty;
};
const handleSelectedEmoji = (newEmoji) => {
    activeEmoji.value = newEmoji;
    setTimeout(() => {
        activeEmoji.value = null;
    }, 3000);
};
function handleAttackMonster(monster) {
    selectedMonster.value = monster;

    moveToMonster(monster);
}

function getAdjacentTile(monster) {
    const options = [
        { x: monster.x + 1, y: monster.y },
        { x: monster.x - 1, y: monster.y },
        { x: monster.x, y: monster.y + 1 },
        { x: monster.x, y: monster.y - 1 },
    ];

    return options.find((t) => !isBlocked(t.x, t.y)) || null;
}

function moveToMonster(monster) {
    const target = getAdjacentTile(monster);

    if (!target) return;

    moveQueue.length = 0;

    let x = player.x;
    let y = player.y;

    while (x !== target.x || y !== target.y) {
        if (x < target.x) x++;
        else if (x > target.x) x--;
        else if (y < target.y) y++;
        else if (y > target.y) y--;

        moveQueue.push({ x, y });
    }

    processQueue(() => {
        startBattle(monster);
    });
}
function startBattle(monster) {
    if (!monster) return;
    pveRef.value.openBattle(monster);
}
function startPvPBattle(id, battle) {
    if (!battle) return;
    pvpRef.value?.openPvPBattle(id, battle);
}
function registerPvpListener() {
    window.Echo.channel(`player.${props.playerData.data.id}`).listen(
        ".pvp.started",
        (e) => {
            const me = props.playerData.data.id;

            let enemy = null;

            if (e.attacker.id === me) {
                enemy = e.defender;
            }

            if (e.defender.id === me) {
                enemy = e.attacker;
            }

            if (!enemy) {
                return;
            }

            if (!pvpRef.value) {
                return;
            }

            pvpRef.value.openPvPBattle(e.battle?.id, enemy);
        },
    );
}

function zoneStateListener() {
    const mapId = props.current_map?.map_id;

    window.Echo.channel(`zone.${mapId}`).listen(".zone.state.updated", (e) => {
        switch (e.payload.type) {
            case "player.update":
                updatePlayers(e);
                break;

            case "player.leave":
                removePlayer(e);
                break;

            case "player.join":
                joinPlayer(e.payload);
                break;
        }
    });
}

function joinPlayer(e) {
    const existing = players.value.find((p) => p.id === e.id);

    // ❌ Do not duplicate players
    if (existing) return;

    players.value.push({
        id: e.id,
        name: e.name,
        class_type: e.class_type,
        current_map_id: e.current_map_id,

        x: Number(e.x),
        y: Number(e.y),

        renderX: Number(e.x) * tileSize,
        renderY: Number(e.y) * tileSize,
        targetX: Number(e.x) * tileSize,
        targetY: Number(e.y) * tileSize,

        direction: e.direction ?? "down",
        walking: false,

        // optional extras (safe if present)
        wing: e.wing ?? null,
        guild: e.guild ?? null,
    });
}

function removePlayer(e) {
    const id = e.id ?? e.payload?.id;

    players.value = players.value.filter((player) => {
        return Number(player.id) !== Number(id);
    });
}
function updatePlayers(e) {
    players.value = players.value.map((player) => {
        const isPlayer = Number(player.id) === Number(e.payload.id);

        const isOpponent = Number(player.id) === Number(e.payload.opponent_id);

        if (!isPlayer && !isOpponent) {
            return player;
        }

        return {
            ...player,
            ...normalizeZoneUpdate({
                ...e.payload,

                // 🔥 assign correct opponent per player
                opponent_id: isPlayer ? e.payload.opponent_id : e.payload.id,
            }),
        };
    });
}
function normalizeZoneUpdate(e) {
    const update = {};

    // core stats
    if (e.current_health !== undefined) {
        update.current_health = e.current_health;
    }

    if (e.max_health !== undefined) {
        update.max_health = e.max_health;
    }

    if (e.current_mana !== undefined) {
        update.current_mana = e.current_mana;
    }
    if (e.max_mana !== undefined) {
        update.max_mana = e.max_mana;
    }

    // combat
    if (e.in_pvp !== undefined) {
        update.in_pvp = e.in_pvp;
    }

    if (e.opponent_id !== undefined) {
        update.opponent_id = e.opponent_id;
    }

    if (e.opponent_current_health !== undefined) {
        update.current_health = e.current_health;
    }

    // status
    if (e.status !== undefined) {
        update.status = e.status;
    }

    if (e.stun !== undefined) {
        update.stun = e.stun;
    }

    // map
    if (e.current_map_id !== undefined) {
        update.current_map_id = e.current_map_id;
    }

    // visuals
    if (e.wing !== undefined) {
        update.wing = e.wing.gear.name;
    }

    if (e.emoji !== undefined) {
        update.emoji = e.emoji;
    }
    if (e.current_experience !== undefined) {
        update.current_experience = e.current_experience;
    }

    return update;
}

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
    const mapElement = e.currentTarget;
    const rect = mapElement.getBoundingClientRect();

    const actualWidth = mapWidth * tileSize;
    const actualHeight = mapHeight * tileSize;
    const scaleX = rect.width / actualWidth;
    const scaleY = rect.height / actualHeight;

    const adjustedX = (e.clientX - rect.left) / scaleX;
    const adjustedY = (e.clientY - rect.top) / scaleY;

    const targetX = Math.floor(adjustedX / tileSize);
    const targetY = Math.floor(adjustedY / tileSize);

    if (
        targetX < 0 ||
        targetX >= mapWidth ||
        targetY < 0 ||
        targetY >= mapHeight
    )
        return;
    if (isBlocked(targetX, targetY)) return;

    // Gaya ng moveToMonster - i-clear ang queue at gumawa ng bagong path
    moveQueue.length = 0;

    let x = player.x;
    let y = player.y;

    while (x !== targetX || y !== targetY) {
        if (x < targetX) x++;
        else if (x > targetX) x--;
        else if (y < targetY) y++;
        else if (y > targetY) y--;

        moveQueue.push({ x, y });
    }

    processQueue();
}

function processQueue(onFinish = null) {
    if (player.moving) return;

    if (!moveQueue || moveQueue.length === 0) {
        onFinish?.();
        return;
    }

    const next = moveQueue.shift();

    if (!next) {
        return;
    }

    const dx = next.x - player.x;
    const dy = next.y - player.y;

    if (dx > 0) player.direction = "right";
    else if (dx < 0) player.direction = "left";
    else if (dy > 0) player.direction = "down";
    else if (dy < 0) player.direction = "up";

    player.moving = true;

    smoothMove(player, next.x * tileSize, next.y * tileSize, () => {
        player.x = next.x;
        player.y = next.y;
        player.moving = false;

        // If there are no more queued moves, send the final position.
        if (!moveQueue || moveQueue.length === 0) {
            updatePlayerMovement(player.x, player.y, player.direction);

            // call onFinish when provided (e.g. to start battle)
            onFinish?.();
            return;
        }

        // otherwise continue processing the remaining queue
        processQueue(onFinish);
    });
}
function isBlocked(x, y) {
    if (x < 0 || y < 0) return true;

    if (x >= mapWidth || y >= mapHeight) return true;

    return map[y][x] === 1;
}

function smoothMove(entity, targetX, targetY, callback = null) {
    const speed = 2.5; // same speed ng monster movement

    function animate() {
        const dx = targetX - entity.renderX;
        const dy = targetY - entity.renderY;
        const distance = Math.sqrt(dx * dx + dy * dy);

        if (distance <= speed) {
            entity.renderX = targetX;
            entity.renderY = targetY;
            callback?.();
            return;
        }

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

        // send movement update after single-step keyboard move completes
        updatePlayerMovement(player.x, player.y, player.direction);
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
const players = ref([]);
const page = usePage();
const myPlayerId = page.props.auth?.player?.id;
let isSendingMove = false;
let pendingMove = null;

async function updatePlayerMovement(x, y, dir) {
    // overwrite pending move instead of spamming requests
    pendingMove = { x, y, dir };

    if (isSendingMove) return;

    isSendingMove = true;

    while (pendingMove) {
        const { x, y, dir } = pendingMove;
        pendingMove = null;

        try {
            await axios.post("/update-player-move", {
                x,
                y,
                dir,
            });
        } catch (err) {
            console.error("Movement update failed:", err);
        }
    }

    isSendingMove = false;
}

async function getPlayers() {
    const res = await axios.get("/streams/get-players");

    players.value = res.data.map((p) => ({
        ...p,
        renderX: p.x * tileSize,
        renderY: p.y * tileSize,
        targetX: p.x * tileSize,
        targetY: p.y * tileSize,
        walking: false,
        current_level: p.current_level,
        current_gold: p.current_gold,
        in_pvp: p.in_pvp,
        wing: p.wing?.gear?.name || null,
        guild: p.guild,
    }));
}

/**
 * CREATE / UPDATE PLAYER
 */
function updatePlayerPosition(data) {
    let p = players.value.find((x) => x.id === data.id);

    if (!p) {
        p = {
            ...data,
            x: data.x,
            y: data.y,
            renderX: data.x * tileSize,
            renderY: data.y * tileSize,
            targetX: data.x * tileSize,
            targetY: data.y * tileSize,
            walking: false,
            direction: data.direction ?? "down",
        };

        players.value.push(p);
        return;
    }

    if (p.x === data.x && p.y === data.y) return;

    // ✅ IMPORTANT: store previous position FIRST
    const prevX = p.x;
    const prevY = p.y;

    const dx = data.x - prevX;
    const dy = data.y - prevY;

    if (Math.abs(dx) > Math.abs(dy)) {
        p.direction = dx > 0 ? "right" : "left";
    } else if (dy !== 0) {
        p.direction = dy > 0 ? "down" : "up";
    }

    // update AFTER direction calc
    p.x = data.x;
    p.y = data.y;

    p.targetX = data.x * tileSize;
    p.targetY = data.y * tileSize;

    p.walking = true;

    smoothMovePlayers(p, p.targetX, p.targetY, () => {
        p.walking = false;
    });
}

function smoothMovePlayers(entity, targetX, targetY, callback = null) {
    const baseSpeed = 2.2;

    let vx = 0;
    let vy = 0;
    let cancelled = false;

    entity._moveToken = Symbol(); // unique movement ID
    const token = entity._moveToken;

    function animate() {
        // 🚨 cancel old movement if new one started
        if (entity._moveToken !== token) return;

        if (document.hidden) {
            requestAnimationFrame(animate);
            return;
        }

        const dx = targetX - entity.renderX;
        const dy = targetY - entity.renderY;

        const distance = Math.sqrt(dx * dx + dy * dy);

        if (distance <= 1) {
            entity.renderX = targetX;
            entity.renderY = targetY;

            callback?.();
            return;
        }

        const nx = dx / distance;
        const ny = dy / distance;

        vx += (nx * baseSpeed - vx) * 0.25;
        vy += (ny * baseSpeed - vy) * 0.25;

        entity.renderX += vx;
        entity.renderY += vy;

        requestAnimationFrame(animate);
    }

    animate();
}

function playersPositionListener() {
    const mapId = props.current_map?.map_id;

    window.Echo.channel(`world.map.${mapId}`).listen(".player.moved", (e) => {
        const id = Number(e.player_id);

        if (id === myPlayerId) return;
        updatePlayerPosition({
            id,
            x: Number(e.x),
            y: Number(e.y),
            direction: e.direction,
        });
    });
}

async function getMyParty() {
    const res = await axios.get("/get-party");
    party.value = res.data;
}
onMounted(() => {
    // window.addEventListener("keydown", handleKey);
    moveMonsters();
    getPlayers();
    getMyParty();
    playersPositionListener();
    registerPvpListener();
    zoneStateListener();
    console.log(import.meta.env.VITE_PUSHER_APP_KEY);
});
const filteredMaps = computed(() => {
    return props.all_maps.data.filter(
        (map) => !map.name.includes("Underground"),
    );
});
const undergroundMap = computed(() => {
    if (!props.current_map) return null;

    const maps = props.all_maps?.data ?? props.all_maps ?? [];

    if (!Array.isArray(maps)) return null;

    return (
        maps.find(
            (map) =>
                map.name?.toLowerCase() ===
                `${props.current_map.name?.toLowerCase()} underground`,
        ) || null
    );
});

const portalPositions = {
    "Valdora Grassland Underground": { x: 750, y: 380 },
    "Dark Forest Underground": { x: 1100, y: 140 },
    "Crystal Cave Underground": { x: 800, y: 80 },
    "Volcanic Wasteland Underground": { x: 940, y: 60 },
    "Sky Islands Underground": { x: 1100, y: 190 },
};
const getPortalPosition = (mapName) => {
    return portalPositions[mapName] || { x: 100, y: 100 };
};
function handleMapPortal() {
    router.visit(route("world.map", undergroundMap.value?.map_id), {
        onFinish: () => {
            pushAlert(undergroundMap.value.name, "success");
        },
    });
}

watch(
    () => props.monsters,
    () => {
        loadMonsters();
    },
    { immediate: true },
);
</script>
<template>
    <Head title="Wisteria Online - MMORPG" />
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
            <div class="weather-layer">
                <span
                    v-for="i in 40"
                    :key="i"
                    class="sparkle"
                    :style="{
                        left: Math.random() * 100 + '%',
                        top: Math.random() * 100 + '%',
                        animationDelay: Math.random() * 5 + 's',
                        animationDuration: 3 + Math.random() * 5 + 's',
                    }"
                ></span>
            </div>

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
            <!-- <div class="bg-white">{{ players }}</div> -->
            <Emoji @select="handleSelectedEmoji" />
            <Tutorial v-if="playerData.data.current_level <= 6" />
            <Portal
                v-if="undergroundMap"
                :x="getPortalPosition(undergroundMap.name).x"
                :y="getPortalPosition(undergroundMap.name).y"
                @open="handleMapPortal"
            />
            <!-- PLAYER -->
            <Player
                :player="player"
                :playerData="playerData.data"
                :activeEmoji="activeEmoji"
                :all_maps="filteredMaps"
                :tileSize="tileSize"
            />
            <Players
                :players="players"
                :player="playerData.data"
                :current_map="current_map"
                @open-battle="startPvPBattle"
                :tileSize="tileSize"
            />
            <!-- HUD COMPONENTS -->
            <ActiveBuffs :player="playerData.data" />
            <Menu
                :classSkills="classSkills.data"
                :all_maps="filteredMaps"
                :player="playerData.data"
                @updatePlayer="updatePlayer"
                @updateParty="updateParty"
            />
            <PlayerStat :player="player" />
            <PartyList :players="players" :party="party" />
            <TownSquareNPC
                :current_map="current_map"
                :all_maps="filteredMaps"
                :player="player"
            />
            <PlayerSkill :playerSkills="playerSkills.data" />
            <WorldChat />
            <PvE
                ref="pveRef"
                :player="player"
                :monsters="monsters"
                :tileSize="tileSize"
                @click-monster="handleAttackMonster"
            />
            <PvP ref="pvpRef" :player="player" :tileSize="tileSize" />
        </div>
    </GameLayout>
</template>
<style scoped>
.tile {
    position: absolute;
    box-sizing: border-box;
    pointer-events: none;
    box-sizing: border-box;
    border: 1px solid rgba(255, 255, 255, 0.03);
    color: red;
}
.game-map {
    position: relative;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;

    border: 4px solid #374151;
    overflow: hidden;
    cursor:
        url("/move-cursor.cur") 16 16,
        pointer;
    image-rendering: pixelated;
}
.game-map.blocked {
    cursor: not-allowed;
}

.wall {
    background: rgba(255, 0, 0, 0.2);
}

/* =========================
   WEATHER LAYER
========================= */

.weather-layer {
    position: absolute;
    inset: 0;

    overflow: hidden;
    pointer-events: none;
}

/* =========================
   MAGIC SPARKLES
========================= */

.sparkle {
    position: absolute;

    width: 4px;
    height: 4px;

    border-radius: 999px;

    background: white;

    box-shadow:
        0 0 6px rgba(255, 255, 255, 0.9),
        0 0 12px rgba(168, 85, 247, 0.8),
        0 0 20px rgba(34, 211, 238, 0.7);

    opacity: 0;

    animation: glitterFloat linear infinite;
}

/* random size variation */
.sparkle:nth-child(3n) {
    width: 2px;
    height: 2px;
}

.sparkle:nth-child(5n) {
    width: 6px;
    height: 6px;
}

/* =========================
   FLOATING GLITTER MOTION
========================= */

@keyframes glitterFloat {
    0% {
        transform: translateY(20px) scale(0.6);
        opacity: 0;
    }

    15% {
        opacity: 1;
    }

    50% {
        transform: translateY(-40px) translateX(10px) scale(1);
        opacity: 0.9;
    }

    100% {
        transform: translateY(-100px) translateX(-10px) scale(0.4);
        opacity: 0;
    }
}
</style>
