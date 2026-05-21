<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";

const props = defineProps({
    player: Object,
});

/**
 * CURRENT TIME
 */
const now = ref(Math.floor(Date.now() / 1000));

setInterval(() => {
    now.value = Math.floor(Date.now() / 1000);
}, 1000);

const buffIcons = {
    exp: "/potions/EXP Potion.png",
    attack: "/potions/Attack Potion.png",
    defense: "/potions/Defense Potion.png",
    speed: "/potions/Speed Potion.png",
    hp: "/potions/HP Potion.png",
    crit: "/potions/Critical Potion.png",
    evasion: "/potions/Evasion Potion.png",
    secret: "/potions/Secret Potion.png",
    recovery: "/potions/Recovery Potion.png",
};
const buffs = computed(() => {
    if (!props.player?.active_buff_effects) return [];

    return props.player.active_buff_effects
        .filter((buff) => buff.expires_at > now.value)
        .map((buff, index) => ({
            id: index,
            ...buff,
            remaining: buff.expires_at - now.value,
            icon: buffIcons[buff.stat] || "/potions/default.png",
        }));
});

const formatTime = (sec) => {
    const m = Math.floor(sec / 60);
    const s = sec % 60;
    return `${m}:${s.toString().padStart(2, "0")}`;
};

let interval;

onMounted(() => {
    interval = setInterval(() => {
        buffs.value.forEach((b) => {
            if (b.remaining > 0) b.remaining--;
        });

        buffs.value = buffs.value.filter((b) => b.remaining > 0);
    }, 1000);
});

onUnmounted(() => clearInterval(interval));
</script>

<template>
    <div class="buff-bar">
        <div v-for="buff in buffs" :key="buff.id" class="buff-item">
            <img :src="buff.icon" class="buff-icon" />

            <!-- TIMER OVERLAY -->
            <div class="buff-timer">
                {{ formatTime(buff.remaining) }}
            </div>
        </div>
    </div>
</template>

<style scoped>
.buff-bar {
    position: absolute;
    top: 10px;
    left: 350px;
    display: flex;
    gap: 6px;
    z-index: 1000;
}

/* 🔥 BIGGER ICON CONTAINER */
.buff-item {
    width: 45px;
    height: 45px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* 🔥 BIGGER ICON */
.buff-icon {
    width: 45px;
    height: 45px;
    object-fit: cover;
    image-rendering: pixelated;
    border-radius: 6px;
    box-shadow: 0 0 6px rgba(0, 0, 0, 0.5);
}

/* 🔥 BIGGER TIMER */
.buff-timer {
    position: absolute;
    bottom: 3px;
    right: 3px;

    font-size: 10px;
    color: #ffffff;
    text-shadow: 0 0 4px black;

    background: rgba(0, 0, 0, 0.55);
    padding: 2px 4px;
    border-radius: 4px;
}
</style>
