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
const talentIcons = {
    attack: "/talents/True Strike.png",
    crit: "/talents/Critical Focus.png",
    defense: "/talents/Iron Skin.png",
    hp: "/talents/Health Boost.png",
    mp: "/talents/Mana Flow.png",
    evasion: "/talents/Evasive Dance.png",
    speed: "/talents/Swift Step.png",
    stun: "/talents/Force Break.png",
};
const talents = computed(() => {
    const raw = props.player?.selected_talent_skills || [];

    return raw
        .map((t) => {
            // step 1: parse outer string
            let parsed = typeof t === "string" ? JSON.parse(t) : t;

            // step 2: flatten inner array
            if (Array.isArray(parsed)) {
                return parsed;
            }

            return parsed;
        })
        .flat()
        .map((talent, index) => ({
            id: index,
            ...talent,
            icon: talentIcons[talent.stat] ?? "/talents/default.png",
        }));
});
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
const allStatusIcons = computed(() => {
    return [
        ...buffs.value.map((b) => ({ ...b, type: "buff" })),
        ...talents.value.map((t) => ({ ...t, type: "talent" })),
    ];
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
        <div
            v-for="item in allStatusIcons"
            :key="item.type + '-' + item.id"
            class="buff-item"
        >
            <img :src="item.icon" class="buff-icon" />

            <!-- only buffs have timer -->
            <div v-if="item.type === 'buff'" class="buff-timer">
                {{ formatTime(item.remaining) }}
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
