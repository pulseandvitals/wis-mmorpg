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
    width: 160px;
    height: 140px;
    cursor: pointer;

    /* MMO FLOOR PERSPECTIVE */
    transform: translate(-50%, -50%) perspective(900px) rotateX(65deg)
        skewX(-10deg);
    transform-origin: center;
}

/* =========================
   MOVING AURA FIELD
========================= */
.rift-aura {
    position: absolute;
    inset: -28px;
    border-radius: 65%;

    background: radial-gradient(
        ellipse at center,
        rgba(168, 85, 247, 0.35),
        rgba(34, 211, 238, 0.18),
        transparent 70%
    );

    filter: blur(22px);
    animation: auraFloat 6s ease-in-out infinite;
}

/* =========================
   GROUND BASE
========================= */
.rift-base {
    position: absolute;
    inset: 10px;
    border-radius: 60%;

    background: linear-gradient(135deg, #05050a 0%, #1a0b2e 40%, #000000 100%);

    box-shadow:
        inset 0 0 30px rgba(0, 0, 0, 0.95),
        0 0 25px rgba(147, 51, 234, 0.35);
}

/* =========================
   CRACKED RIM
========================= */
.rift-rim {
    position: absolute;
    inset: 12px;
    border-radius: 60%;

    border: 1px solid rgba(192, 132, 252, 0.25);

    box-shadow:
        inset 0 0 20px rgba(168, 85, 247, 0.25),
        0 0 15px rgba(0, 0, 0, 0.6);
}

/* =========================
   ABYSS DEPTH (LIVING VOID)
========================= */
.rift-abyss {
    position: absolute;
    inset: 24px;
    border-radius: 62%;

    background: radial-gradient(
        ellipse at center,
        #05050a 0%,
        #000000 60%,
        #000000 100%
    );

    animation: abyssPulse 5s ease-in-out infinite;
}

/* =========================
   ENERGY CORE
========================= */
.rift-core {
    position: absolute;
    inset: 18px;
    border-radius: 60%;

    background: radial-gradient(
        ellipse at center,
        rgba(34, 211, 238, 0.45),
        rgba(168, 85, 247, 0.3),
        transparent 70%
    );

    filter: blur(2px);
    animation: coreBreath 2.8s ease-in-out infinite;
}

/* =========================
   ROTATING DIMENSIONAL FIELD
========================= */
.rift-wave {
    position: absolute;
    inset: 0;
    border-radius: 60%;

    background: conic-gradient(
        from 0deg,
        rgba(168, 85, 247, 0),
        rgba(168, 85, 247, 0.25),
        rgba(34, 211, 238, 0.2),
        rgba(168, 85, 247, 0)
    );

    animation: spin 4s linear infinite;

    filter: blur(1px);
    opacity: 0.75;
}

/* =========================
   ENTER TEXT
========================= */
.rift-enter {
    position: absolute;
    inset: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 12px;
    font-weight: 900;
    letter-spacing: 2px;

    color: rgba(255, 255, 255, 0.9);

    text-shadow:
        0 0 10px rgba(168, 85, 247, 0.9),
        0 0 20px rgba(34, 211, 238, 0.6),
        0 0 30px rgba(0, 0, 0, 0.8);

    pointer-events: none;

    transform: translateZ(20px);

    animation: enterPulse 2.5s ease-in-out infinite;
}

/* =========================
   ANIMATIONS
========================= */

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

@keyframes auraFloat {
    0% {
        transform: translateY(0px) scale(1);
        opacity: 0.4;
    }
    50% {
        transform: translateY(-4px) scale(1.08);
        opacity: 0.85;
    }
    100% {
        transform: translateY(0px) scale(1);
        opacity: 0.4;
    }
}

@keyframes coreBreath {
    0% {
        transform: scale(1);
        opacity: 0.6;
    }
    50% {
        transform: scale(1.2);
        opacity: 1;
    }
    100% {
        transform: scale(1);
        opacity: 0.6;
    }
}

@keyframes abyssPulse {
    0% {
        filter: brightness(0.8);
    }
    50% {
        filter: brightness(1.35);
    }
    100% {
        filter: brightness(0.8);
    }
}

@keyframes enterPulse {
    0% {
        opacity: 0.7;
        transform: translateZ(20px) scale(1);
    }
    50% {
        opacity: 1;
        transform: translateZ(20px) scale(1.08);
    }
    100% {
        opacity: 0.7;
        transform: translateZ(20px) scale(1);
    }
}
</style>
