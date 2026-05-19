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
const elementIcons = {
    water: "💧",
    wind: "🌪️",
    fire: "🔥",
    earth: "🪨",
    electric: "⚡",
};

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

                <!-- ELEMENT ICON -->
                <span class="monster-element" :class="monster.element">
                    {{ elementIcons[monster.element] }}
                </span>
            </div>
        </div>

        <!-- SPRITE -->
        <img
            class="monster-sprite"
            :src="`/monster_sprites/${monster.name.replace(' (Elite)', '')}/${monster.moving ? 'walk' : 'idle'}-${monster.direction}.gif`"
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

.monster-name {
    display: flex;
    align-items: center;
    gap: 6px;
}

.monster-element {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 12px;
    height: 15px;
    border-radius: 50%;
    font-size: 13px;
    color: white;
}

/* ELEMENT COLORS */
.monster-element.water {
    background: #3498db;
}

.monster-element.wind {
    background: #2ecc71;
}

.monster-element.fire {
    background: #e74c3c;
}

.monster-element.earth {
    background: #8e6e53;
}

.monster-element.electric {
    background: #f1c40f;
    color: black;
}

/* 🔥 HOVER / FOCUS EFFECT */
.monster:hover .monster-sprite {
    filter: drop-shadow(0 0 6px rgba(255, 0, 0, 0.9));
    transform: scale(1.08);
}
</style>
