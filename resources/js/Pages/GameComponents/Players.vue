<script setup>
import { pushAlert } from "@/Stores/GlobalAlert";
import { ref } from "vue";
const props = defineProps({
    player: Object,
    players: Object,
    current_map: Object,
    tileSize: {
        type: Number,
        required: true,
    },
});
const emit = defineEmits(["open-battle"]);
const cooldown = new Set();
const viewGear = ref(false);

async function handleClick(player) {
    if (props.current_map?.is_safe_zone) {
        await getPlayerGear(player.id);
        viewGear.value = true;
    } else {
        await requestBattle(player);
    }
}
async function getPlayerGear(playerId) {
    try {
        const res = await axios.get(`/player/${playerId}/gear`);
        return res.data.gear;
    } catch (e) {
        console.error(e);
        pushAlert("Failed to load player gear.", "error");
        return null;
    }
}

async function requestBattle(player) {
    if (cooldown.has(player.id)) return;
    if (props.current_map?.is_safe_zone) return;
    cooldown.add(player.id);

    setTimeout(() => cooldown.delete(player.id), 1500);

    if (player.in_pvp) return;
    if (player.id === props.player?.id) return;

    try {
        const res = await axios.post("/pvp/request", {
            target_id: player.id,
        });

        if (res.data.in_battle) {
            emit("open-battle", res.data.battle);
            pushAlert(
                "PvP Battle Started!",
                `You are now battling ${player.name}.`,
                "success",
            );
        }
    } catch (e) {
        pushAlert(e.message, "Failed to request PvP battle.");
        console.error(e);
    }
}

const getSpriteFolder = (p) => {
    return p?.wing ? `${p.class_type} ${p.wing}` : p.class_type;
};
</script>

<template>
    <div>
        <!-- <div class="bg-white font-xl z-[9999] w-50">{{ players }}</div> -->
        <div
            v-for="p in players"
            :key="p.id"
            class="world-player"
            :class="{
                attackable: !current_map?.is_safe_zone,
                self: p.id === player?.id,
            }"
            :style="{
                transform: `translate(${p.renderX}px, ${p.renderY}px)`,
            }"
            @click="handleClick(p)"
        >
            <!-- NAME TAG -->
            <div class="name-tag">
                <p :class="{ 'text-red-500': !current_map?.is_safe_zone }">
                    {{ p.name }}
                </p>
            </div>
            <img
                :src="`/sprites/${getSpriteFolder(p)}/${p.walking ? 'walk' : 'idle'}-${p.direction}.gif`"
                class="sprite"
            />

            <!-- HP BAR -->
            <div class="player-stats">
                <!-- HP -->
                <div class="stat-row">
                    <div class="stat-bar hp-bar">
                        <div
                            class="stat-fill hp-fill"
                            :style="{
                                width:
                                    (p.current_health / p.max_health) * 100 +
                                    '%',
                            }"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.world-player {
    position: absolute;
    width: 64px;
    height: 64px;
    text-align: center;
    pointer-events: auto;
}
.world-player.attackable:hover .sprite {
    filter: drop-shadow(0 0 6px rgba(255, 0, 0, 0.9));
    transform: scale(1.08);
    cursor:
        url("/attack-cursor.cur") 16 16,
        crosshair;
}
.world-player.self {
    pointer-events: none;
}
/* SPRITE */
.sprite {
    width: 100%;
    height: 100%;
    image-rendering: pixelated;
}

/* NAME */
.name-tag {
    position: absolute;
    top: -5px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 10px;
    font-weight: bold;
    color: white;
    text-shadow: 0 0 3px black;
    pointer-events: none;
    white-space: nowrap;
    padding: 0 5px;
    background-color: rgb(0, 0, 0, 0.5);
}

.player-stats {
    position: absolute;
    bottom: -20%;
    left: 50%;
    transform: translateX(-50%);
    width: 90%;
    max-width: 55px;
    display: flex;
    flex-direction: column;
    gap: 1px;
    padding: 0.5px;
    border: 1px solid #f1f1f1;
}

.stat-row {
    display: flex;
    align-items: center;
    gap: 2px;
}

.stat-bar {
    flex: 1;
    height: 5px;
    background: #f1f1f1;
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 0px;
    overflow: hidden;
}

.stat-fill {
    height: 100%;
    transition: width 0.2s ease;
}

.hp-bar {
    border-color: #c0392b;
}

.hp-fill {
    background: linear-gradient(to right, #e74c3c, #c0392b);
}

.mp-bar {
    border-color: #2980b9;
}

.mp-fill {
    background: linear-gradient(to right, #3498db, #2980b9);
}
</style>
