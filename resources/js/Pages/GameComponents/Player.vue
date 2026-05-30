<script setup>
import { useForm } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";

const props = defineProps({
    player: {
        type: Object,
        required: true,
    },
    all_maps: {
        type: Object,
        required: true,
    },
    playerData: {
        type: Object,
        required: true,
    },
    tileSize: {
        type: Number,
        required: true,
    },
    activeEmoji: {
        type: String,
    },
});

const showReviveModal = ref(false);
const form = useForm({});
const map = props.all_maps.find((map) => map.name === "Wisteria Town");
watch(
    () => props.player.current_health,
    (hp) => {
        if (hp <= 0) {
            showReviveModal.value = true;
        }
    },
);

const reviveInTown = () => {
    showReviveModal.value = false;

    form.get(route("world.map", map.map_id), {
        onFinish: () => pushAlert(map.name, "success"),
    });
};

const spriteFolder = computed(() => {
    return props.playerData.wing
        ? `${props.playerData.class_type} ${props.playerData.wing?.gear?.name}`
        : props.playerData.class_type;
});

const spritePath = computed(() => {
    return `/sprites/${spriteFolder.value}/${
        props.player.moving ? "walk" : "idle"
    }-${props.player.direction}.gif`;
});
</script>

<template>
    <div
        class="player"
        :style="{
            width: tileSize + 'px',
            height: tileSize + 'px',
            transform: `translate(${player.renderX}px, ${player.renderY}px)`,
        }"
    >
        <!-- EMOJI REACTION -->
        <div v-if="activeEmoji" class="player-emoji">
            <img :src="activeEmoji" />
        </div>
        <!-- GUILD NAME (ABOVE PLAYER NAME) -->
        <div v-if="player.guild" class="guild-name">
            <{{ player.guild.name }}>
        </div>

        <!-- NAME -->
        <div class="player-name">
            {{ player.name }}
        </div>

        <img
            class="sprite"
            :class="{ dead: player.current_health <= 0 }"
            :src="spritePath"
        />

        <!-- HP & MP BARS -->
        <div class="player-stats">
            <!-- HP -->
            <div class="stat-row">
                <div class="stat-bar hp-bar">
                    <div
                        class="stat-fill hp-fill"
                        :style="{
                            width:
                                (player.current_health / player.max_health) *
                                    100 +
                                '%',
                        }"
                    />
                </div>
            </div>

            <!-- MP -->
            <div class="stat-row">
                <div class="stat-bar mp-bar">
                    <div
                        class="stat-fill mp-fill"
                        :style="{
                            width:
                                (player.current_mana / player.max_mana) * 100 +
                                '%',
                        }"
                    />
                </div>
            </div>
        </div>
    </div>

    <!-- REVIVE MODAL -->
    <div v-if="showReviveModal" class="revive-overlay">
        <div class="revive-modal">
            <p>You died.</p>

            <button @click="reviveInTown">Revive In Town</button>
        </div>
    </div>
</template>

<style scoped>
.player {
    position: absolute;
    text-align: center;
}

.player-name {
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
.guild-name {
    position: absolute;
    top: -22px;
    left: 50%;
    transform: translateX(-50%);

    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.5px;

    color: #fbbf24; /* gold-ish MMO guild color */

    padding: 1px 2px;

    background: rgba(0, 0, 0, 0.55);

    text-shadow: 0 0 6px rgba(251, 191, 36, 0.25);

    white-space: nowrap;
    pointer-events: none;

    transition: all 0.15s ease;
}

.sprite {
    width: 100%;
    height: 100%;
    object-fit: contain;
    image-rendering: pixelated;
}

.sprite.dead {
    filter: grayscale(1) brightness(0.5);
    opacity: 0.7;
}

.player-stats {
    position: absolute;
    bottom: -25%;
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

/* =========================
   REVIVE MODAL
========================= */

.revive-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);

    display: flex;
    align-items: center;
    justify-content: center;

    z-index: 9999;
}

.revive-modal {
    background: #1a1a1a;
    border: 1px solid #555;
    padding: 20px;
    border-radius: 8px;

    display: flex;
    flex-direction: column;
    gap: 10px;

    color: white;
    min-width: 220px;
    text-align: center;
}

.revive-modal button {
    background: #dc2626;
    border: none;
    color: white;
    padding: 8px;
    border-radius: 4px;
    cursor: pointer;
}
.player-emoji {
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 14px;
    pointer-events: none;
    z-index: 9999;
    animation: emojiFloat 0.8s ease-out;
}

@keyframes emojiFloat {
    0% {
        transform: translate(-50%, 10px);
        opacity: 0;
    }
    100% {
        transform: translate(-50%, 0px);
        opacity: 1;
    }
}
</style>
