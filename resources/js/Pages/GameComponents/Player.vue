<script setup>
import { computed } from "vue";

const props = defineProps({
    player: {
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
});
const spriteFolder = computed(() => {
    return props.playerData.wing
        ? `${props.playerData.class_type} ${props.playerData.wing?.gear?.name}`
        : props.playerData.class_type;
});
const spritePath = computed(() => {
    return `/sprites/${spriteFolder.value}/${props.player.moving ? "walk" : "idle"}-${props.player.direction}.gif`;
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
        <!-- NAME -->
        <div class="player-name">{{ player.name }}</div>

        <img class="sprite" :src="spritePath" />

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

.sprite {
    width: 100%;
    height: 100%;
    object-fit: contain;
    image-rendering: pixelated;
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
</style>
