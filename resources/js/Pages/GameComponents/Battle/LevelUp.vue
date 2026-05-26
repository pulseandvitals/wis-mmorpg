<template>
    <transition name="pop">
        <div v-if="visible" class="level-up-overlay">
            <div class="level-up-box">
                <div class="title">LEVEL UP!</div>

                <div class="level">Level {{ level }}</div>

                <div class="message">You feel stronger!</div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    levelUp: Boolean,
    level: Number,
});

const emit = defineEmits(["cleared"]);

const visible = ref(false);

watch(
    () => props.levelUp,
    (val) => {
        if (val) {
            visible.value = true;

            setTimeout(() => {
                visible.value = false;
                emit("cleared");
            }, 5000);
        }
    },
);
</script>

<style scoped>
.level-up-overlay {
    position: absolute; /* 🔥 key change */
    top: 20%;
    left: 50%;
    transform: translateX(-50%);
    z-index: 99999;

    pointer-events: none; /* so it won't block clicks */
}

.level-up-box {
    background: linear-gradient(
        180deg,
        rgba(30, 20, 10, 0.95),
        rgba(15, 10, 5, 0.95)
    );

    border: 1px solid rgba(255, 215, 120, 0.8);
    border-radius: 14px;

    padding: 16px 26px;
    text-align: center;
    color: #f8e7c0;

    box-shadow:
        0 10px 30px rgba(0, 0, 0, 0.6),
        inset 0 0 12px rgba(255, 215, 120, 0.15);

    backdrop-filter: blur(4px);

    animation: levelPop 1.8s ease forwards;
}

/* 🌟 Title (gold fantasy feel) */
.title {
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 2px;
    color: #ffd36a;
    text-shadow:
        0 0 8px rgba(255, 211, 106, 0.6),
        0 2px 2px rgba(0, 0, 0, 0.5);
}

/* 📈 Level text */
.level {
    font-size: 22px;
    font-weight: bold;
    margin-top: 6px;
    color: #fff3d1;
    text-shadow: 0 0 6px rgba(255, 255, 255, 0.2);
}

/* ✨ message */
.message {
    margin-top: 4px;
    font-size: 12px;
    opacity: 0.85;
    color: #d6c6a3;
}

/* 🌟 pop animation (more RPG feel) */
@keyframes levelPop {
    0% {
        opacity: 0;
        transform: translateY(10px) scale(0.85);
    }
    20% {
        opacity: 1;
        transform: translateY(0) scale(1.05);
    }
    40% {
        transform: scale(1);
    }
    100% {
        opacity: 0;
        transform: translateY(-25px) scale(1.02);
    }
}
</style>
