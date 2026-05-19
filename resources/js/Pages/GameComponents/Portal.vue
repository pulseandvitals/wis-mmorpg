<script setup>
defineProps({
    x: Number,
    y: Number,
});
const emit = defineEmits(["open"]);
const clickPortal = () => {
    emit("open");
};
</script>

<template>
    <div
        @click="clickPortal"
        class="rift-wrapper"
        :style="{
            left: x + 'px',
            top: y + 'px',
        }"
    >
        <!-- aura field -->
        <div class="rift-aura"></div>

        <!-- ground tear base -->
        <div class="rift-base"></div>

        <!-- cracked rim -->
        <div class="rift-rim"></div>

        <!-- abyss depth -->
        <div class="rift-abyss"></div>

        <!-- energy core -->
        <div class="rift-core"></div>

        <!-- distortion waves -->
        <div class="rift-wave"></div>

        <!-- label -->
        <div class="rift-enter">WARP</div>
    </div>
</template>
<style scoped>
.rift-wrapper {
    position: absolute;
    width: 150px;
    height: 140px;

    cursor: pointer;

    /* TRUE MMORPG FLOOR PERSPECTIVE */
    transform: translate(-50%, -50%) perspective(800px) rotateX(65deg)
        skewX(-10deg);
    transform-origin: center;
}

/* =========================
   GLOBAL AURA (MAGIC FIELD)
========================= */
.rift-aura {
    position: absolute;
    inset: -20px;
    border-radius: 65%;

    background: radial-gradient(
        ellipse at center,
        rgba(168, 85, 247, 0.25),
        transparent 65%
    );

    filter: blur(20px);
    animation: auraPulse 3s infinite ease-in-out;
}

/* =========================
   BASE GROUND TEAR
========================= */
.rift-base {
    position: absolute;
    inset: 10px;
    border-radius: 60%;

    background: linear-gradient(135deg, #05050a 0%, #1a0b2e 35%, #000000 100%);

    box-shadow:
        inset 0 0 30px rgba(0, 0, 0, 0.95),
        0 0 25px rgba(147, 51, 234, 0.4);
}

/* =========================
   CRACKED RIM (GROUND BREAK)
========================= */
.rift-rim {
    position: absolute;
    inset: 12px;
    border-radius: 60%;

    border: 1px solid rgba(192, 132, 252, 0.25);

    box-shadow:
        inset 0 0 20px rgba(168, 85, 247, 0.2),
        0 0 15px rgba(0, 0, 0, 0.6);
}

/* =========================
   ABYSS DEPTH (HOLE EFFECT)
========================= */
.rift-abyss {
    position: absolute;
    inset: 24px;
    border-radius: 62%;

    background: radial-gradient(
        ellipse at center,
        #000000 0%,
        #020207 40%,
        #000000 100%
    );

    box-shadow: inset 0 0 40px rgba(0, 0, 0, 1);
}

/* =========================
   ENERGY CORE (ACTIVE VOID)
========================= */
.rift-core {
    position: absolute;
    inset: 18px;
    border-radius: 60%;

    background: radial-gradient(
        ellipse at center,
        rgba(34, 211, 238, 0.35),
        rgba(168, 85, 247, 0.25),
        transparent 70%
    );

    filter: blur(2px);
    animation: corePulse 2.2s infinite ease-in-out;
}

/* =========================
   DIMENSIONAL WAVE DISTORTION
========================= */
.rift-wave {
    position: absolute;
    inset: 0;
    border-radius: 60%;

    background: radial-gradient(
        ellipse at center,
        rgba(255, 255, 255, 0.08),
        transparent 60%
    );

    animation: waveExpand 3.5s infinite ease-out;
}

/* =========================
   LABEL
========================= */
.rift-label {
    position: absolute;
    top: -34px;
    left: 50%;
    transform: translateX(-50%) rotateX(0deg);

    color: white;
    font-size: 12px;
    font-weight: bold;

    text-shadow:
        0 0 10px rgba(168, 85, 247, 0.8),
        0 0 20px rgba(0, 0, 0, 0.8);
}

/* =========================
   ANIMATIONS
========================= */

@keyframes corePulse {
    0% {
        transform: scale(1);
        opacity: 0.6;
    }
    50% {
        transform: scale(1.15);
        opacity: 1;
    }
    100% {
        transform: scale(1);
        opacity: 0.6;
    }
}

@keyframes auraPulse {
    0% {
        opacity: 0.4;
        transform: scale(1);
    }
    50% {
        opacity: 0.8;
        transform: scale(1.1);
    }
    100% {
        opacity: 0.4;
        transform: scale(1);
    }
}

@keyframes waveExpand {
    0% {
        transform: scale(0.9);
        opacity: 0.5;
    }
    100% {
        transform: scale(1.4);
        opacity: 0;
    }
}

.rift-enter {
    position: absolute;
    inset: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 12px;
    font-weight: 800;
    letter-spacing: 2px;

    color: rgba(255, 255, 255, 0.9);

    text-shadow:
        0 0 10px rgba(168, 85, 247, 0.9),
        0 0 20px rgba(34, 211, 238, 0.6),
        0 0 30px rgba(0, 0, 0, 0.8);

    pointer-events: none;

    /* keeps it visually stable despite 3D transform */
    transform: translateZ(20px);
}

/* optional: subtle breathing glow on text */
@keyframes enterPulse {
    0% {
        opacity: 0.7;
        transform: translateZ(20px) scale(1);
    }
    50% {
        opacity: 1;
        transform: translateZ(20px) scale(1.05);
    }
    100% {
        opacity: 0.7;
        transform: translateZ(20px) scale(1);
    }
}

.rift-enter {
    animation: enterPulse 2.5s infinite ease-in-out;
}
</style>
