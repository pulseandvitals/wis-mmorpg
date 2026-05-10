<script setup>
const props = defineProps({
    player: {
        type: Object,
        required: true,
    },
    tileSize: {
        type: Number,
        required: true,
    },
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
        <img
            class="sprite"
            :src="`/sprites/${player.className}/${player.moving ? 'walk' : 'idle'}-${player.direction}.gif`"
        />

        <!-- HP & MP BARS -->
        <div class="player-stats">
            <!-- HP -->
            <div class="stat-row">
                <div class="stat-bar hp-bar">
                    <div
                        class="stat-fill hp-fill"
                        :style="{
                            width: (player.hp / player.maxHp) * 100 + '%',
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
                            width: (player.mp / player.maxMp) * 100 + '%',
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
    display: flex;
    flex-direction: column;
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

.player-stats {
    position: absolute;
    bottom: -35%;
    left: 50%;
    transform: translateX(-50%);
    width: 90%;
    max-width: 55px;
    display: flex;
    flex-direction: column;
    gap: 2px;
    background: rgba(0, 0, 0, 0.6);
    padding: 2px;
    border-radius: 3px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-row {
    display: flex;
    align-items: center;
    gap: 2px;
}

.stat-bar {
    flex: 1;
    height: 6px;
    background: rgba(0, 0, 0, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 2px;
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
