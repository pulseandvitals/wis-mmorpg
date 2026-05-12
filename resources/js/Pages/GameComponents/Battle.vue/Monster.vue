<script setup>
const props = defineProps({
    monsters: {
        type: Array,
        required: true,
    },
    tileSize: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(["selectMonster"]);
function selectMonster(monster) {
    emit("selectMonster", monster);
}
</script>
<template>
    <div
        v-for="monster in monsters"
        :key="monster.id"
        class="monster"
        @click="selectMonster(monster)"
        :style="{
            width: tileSize + 'px',
            height: tileSize + 'px',
            transform: `translate(${monster.renderX}px, ${monster.renderY}px)`,
        }"
    >
        <!-- NAME + ELEMENT -->
        <div class="monster-label">
            <div class="monster-name">
                {{ monster.name }}
            </div>
        </div>

        <!-- SPRITE -->
        <img
            class="monster-sprite"
            :src="`/monster_sprites/${monster.name}/${monster.moving ? 'walk' : 'idle'}-${monster.direction}.gif`"
        />
    </div>
</template>
<style scoped>
.monster {
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10;
}

.controls {
    color: white;
    text-align: center;
}
.monster-label {
    position: absolute;
    top: -10px;

    display: flex;
    flex-direction: column;
    align-items: center;

    padding: 5px 6px;

    white-space: nowrap;
}

.monster-name {
    color: #ef4444; /* RED */
    font-size: 10px;
    font-weight: bold;
    padding: 3px 5px;
    background-color: rgb(0, 0, 0, 0.5);
}

.monster-element {
    margin-top: 2px;
}

.element-icon {
    width: 10px;
    height: 10px;

    image-rendering: pixelated;
}
/* SPRITE BASE */
.monster-sprite {
    width: 100%;
    height: 100%;

    object-fit: contain;
    image-rendering: pixelated;

    transition:
        transform 0.15s ease,
        filter 0.15s ease;
}

/* 🔥 HOVER / FOCUS EFFECT */
.monster:hover .monster-sprite {
    filter: drop-shadow(0 0 6px rgba(255, 0, 0, 0.9));
    transform: scale(1.08);
}
</style>
