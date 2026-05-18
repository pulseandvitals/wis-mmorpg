<script setup>
const props = defineProps({
    players: Object,
    tileSize: {
        type: Number,
        required: true,
    },
});
</script>

<template>
    <div>
        <div
            v-for="p in players"
            :key="p.id"
            class="world-player"
            :style="{
                transform: `translate(${p.renderX}px, ${p.renderY}px)`,
            }"
        >
            <!-- NAME TAG -->
            <div class="name-tag">
                {{ p.name }}
            </div>
            <img
                :src="`/sprites/${p.class_type}/${p.walking ? 'walk' : 'idle'}-${p.direction}.gif`"
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
