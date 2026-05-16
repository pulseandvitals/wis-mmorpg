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
            <div class="hp-bar">
                <div
                    class="hp-fill"
                    :style="{
                        width: (p.current_health / p.max_health) * 100 + '%',
                    }"
                />
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

/* HP BAR */
.hp-bar {
    flex: 1;
    height: 5px;
    background: #f1f1f1;
    border: 1px solid rgba(255, 255, 255, 0.15);
    overflow: hidden;
}

.hp-fill {
    height: 100%;
    background: linear-gradient(to right, #e74c3c, #c0392b);
}
</style>
