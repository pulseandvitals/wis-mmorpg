<script setup>
import { router } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import GlobalAlert from "@/Pages/GameComponents/GlobalAlert.vue";
const loading = ref(false);

let removeStart;
let removeFinish;
const currentImage = ref("");
const loadingImages = [
    "/loading_screens/Loading1.png",
    "/loading_screens/Loading2.png",
    "/loading_screens/Loading3.png",
    "/loading_screens/Loading4.png",
];

function getRandomImage() {
    const index = Math.floor(Math.random() * loadingImages.length);
    currentImage.value = loadingImages[index];
}

onMounted(() => {
    removeStart = router.on("start", () => {
        loading.value = true;
        getRandomImage();
    });

    removeFinish = router.on("finish", () => {
        loading.value = false;
    });
});

onMounted(() => {
    removeStart = router.on("start", () => {
        loading.value = true;
        getRandomImage();
    });

    removeFinish = router.on("finish", () => {
        loading.value = false;
    });
});

onUnmounted(() => {
    removeStart();
    removeFinish();
});
</script>

<template>
    <div class="game-wrapper">
        <div class="game-area">
            <transition name="fade">
                <div v-if="loading" class="loading-screen">
                    <img :src="currentImage" class="loading-bg" />

                    <div class="loading-content">
                        <div class="spinner"></div>
                        <div class="loading-text">Loading World...</div>
                    </div>
                </div>
            </transition>
            <global-alert />
            <main class="game-stage" v-if="!loading">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Disable dragging visuals + selection globally */
img,
video,
canvas {
    -webkit-user-drag: none;
    user-drag: none;
    -webkit-user-select: none;
    user-select: none;
}

/* Kill text + UI selection (blue highlight box) */
* {
    user-select: none;
    -webkit-user-select: none;
}

/* Prevent drag ghost on most elements */
[draggable="true"] {
    -webkit-user-drag: none;
}
.game-wrapper {
    width: 100vw;
    height: 100vh;
    background: #111827;

    display: flex;
    justify-content: center;
    align-items: center;

    overflow: hidden; /* important for mobile */
}

.game-area {
    position: relative;

    width: calc(24 * 64px);
    height: calc(13 * 64px);

    transform-origin: center;
    will-change: transform;
}

/* MOBILE SAFE */
@media (max-width: 768px) {
    .loading-text {
        font-size: 12px;
        letter-spacing: 0.2em;
    }

    .spinner {
        width: 48px;
        height: 48px;
    }
}

/* 👇 loading now matches GAME AREA ONLY */
.loading-screen {
    position: absolute;
    inset: 0; /* key change: NOT fixed */

    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.loading-bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.loading-content {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.spinner {
    width: 64px;
    height: 64px;
    border: 4px solid #22d3ee;
    border-top: 4px solid transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
.loading-text {
    margin-top: 20px;
    color: #67e8f9;
    text-transform: uppercase;
    letter-spacing: 0.3em;
}
.fade-enter-active,
.fade-leave-active {
    transition:
        opacity 0.4s ease,
        filter 0.4s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    filter: blur(6px);
}
</style>
