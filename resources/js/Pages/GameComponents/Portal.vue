<script setup></script>
<template>
    <div class="floor-portal">
        <div class="rift"></div>
        <div class="runes"></div>
        <div class="label">ENTER</div>
    </div>
</template>
<style scoped>
.floor-portal {
    position: relative;
    width: 220px;
    height: 160px;

    display: flex;
    align-items: center;
    justify-content: center;

    cursor: pointer;

    /* perspective makes it feel like ground */
    perspective: 800px;
}

/* ===== FLOOR GLOW (SPREAD ON GROUND) ===== */
.floor-portal::before {
    content: "";
    position: absolute;
    width: 260px;
    height: 160px;

    border-radius: 50%;

    background: radial-gradient(
        ellipse at center,
        rgba(0, 255, 255, 0.25),
        rgba(0, 0, 0, 0) 65%
    );

    transform: rotateX(70deg);
    filter: blur(20px);

    animation: floorPulse 3s ease-in-out infinite;
}

/* ===== MAIN RIFT (CRACK IN THE FLOOR) ===== */
.rift {
    position: absolute;
    width: 170px;
    height: 90px;

    border-radius: 50%;

    background: radial-gradient(
        ellipse at center,
        rgba(0, 255, 255, 0.15),
        rgba(0, 0, 0, 0.95) 70%
    );

    transform: rotateX(75deg) scaleY(0.6);

    box-shadow:
        inset 0 0 30px rgba(0, 255, 255, 0.2),
        inset 0 0 60px rgba(0, 0, 0, 1),
        0 0 30px rgba(0, 255, 255, 0.15);

    overflow: hidden;

    animation: riftBreath 3s ease-in-out infinite;
}

/* ===== SWIRLING DEPTH INSIDE ===== */
.rift::before {
    content: "";
    position: absolute;
    inset: -40px;

    background: conic-gradient(
        from 0deg,
        rgba(0, 255, 255, 0),
        rgba(0, 255, 255, 0.35),
        rgba(106, 92, 255, 0.25),
        rgba(0, 255, 255, 0)
    );

    animation: spin 3s linear infinite;
    filter: blur(2px);
    opacity: 0.6;
}

/* ===== FLOOR RUNES (MAGIC CIRCLE) ===== */
.runes {
    position: absolute;
    width: 210px;
    height: 130px;

    border-radius: 50%;

    border: 2px solid rgba(0, 255, 255, 0.25);
    border-top-color: rgba(0, 255, 255, 0.6);
    border-bottom-color: rgba(106, 92, 255, 0.3);

    transform: rotateX(70deg);

    animation: runeSpin 5s linear infinite;
    filter: blur(0.2px);
}

/* ===== TEXT ===== */
.label {
    position: relative;
    z-index: 2;

    font-size: 14px;
    letter-spacing: 3px;
    color: rgba(200, 255, 255, 0.9);

    text-shadow: 0 0 10px rgba(0, 255, 255, 0.6);
    transform: rotateX(20deg);
}

/* ===== HOVER EFFECT ===== */
.floor-portal:hover .rift {
    transform: rotateX(75deg) scaleY(0.65) scale(1.05);
    box-shadow:
        inset 0 0 50px rgba(0, 255, 255, 0.3),
        0 0 60px rgba(0, 255, 255, 0.35);
}

/* ===== ANIMATIONS ===== */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

@keyframes runeSpin {
    from {
        transform: rotateX(70deg) rotate(0deg);
    }
    to {
        transform: rotateX(70deg) rotate(360deg);
    }
}

@keyframes floorPulse {
    0%,
    100% {
        opacity: 0.4;
        transform: rotateX(70deg) scale(1);
    }
    50% {
        opacity: 0.8;
        transform: rotateX(70deg) scale(1.2);
    }
}

@keyframes riftBreath {
    0%,
    100% {
        transform: rotateX(75deg) scaleY(0.6);
    }
    50% {
        transform: rotateX(75deg) scaleY(0.65);
    }
}
</style>
