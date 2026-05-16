<script setup>
import { GlobalAlertStore } from "@/Stores/GlobalAlert";
import { computed } from "vue";

const alert = computed(() => GlobalAlertStore.current);
</script>

<template>
    <transition name="alert">
        <div v-if="alert" class="global-alert" :class="alert.type">
            {{ alert.message }}
        </div>
    </transition>
</template>

<style scoped>
.global-alert {
    position: fixed;
    top: 18px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 999999;

    min-width: 260px;
    max-width: 520px;

    padding: 12px 18px;
    border-radius: 10px;

    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.3px;

    color: #111827; /* dark text for contrast */

    /* 🤍 clean white MMO system panel */
    background: rgba(255, 255, 255, 0.92);
    border: 1px solid rgba(209, 213, 219, 0.9);

    backdrop-filter: blur(10px);

    box-shadow:
        0 10px 30px rgba(0, 0, 0, 0.15),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);

    display: flex;
    align-items: center;
    justify-content: center;

    text-align: center;
}

/* subtle top highlight like MMO UI panels */
.global-alert::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    height: 2px;
    width: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(59, 130, 246, 0.4),
        transparent
    );
    border-radius: 10px 10px 0 0;
}

/* TYPE ACCENTS (still visible but clean) */
.global-alert.info {
    border-color: rgba(59, 130, 246, 0.35);
}

.global-alert.success {
    border-color: rgba(34, 197, 94, 0.35);
}

.global-alert.error {
    border-color: rgba(239, 68, 68, 0.35);
}

.global-alert.warning {
    border-color: rgba(250, 204, 21, 0.45);
}

/* animation */
.alert-enter-active,
.alert-leave-active {
    transition: all 0.25s ease;
}

.alert-enter-from {
    opacity: 0;
    transform: translateX(-50%) translateY(-10px) scale(0.96);
    filter: blur(4px);
}

.alert-leave-to {
    opacity: 0;
    transform: translateX(-50%) translateY(-10px) scale(0.96);
    filter: blur(4px);
}
</style>
