<script setup>
import { ref, onMounted } from "vue";

const showSettings = ref(false);
const isFullscreen = ref(false);
const musicOn = ref(true);

// ----------------------
// FULLSCREEN
// ----------------------
function toggleFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}

onMounted(() => {
    document.addEventListener("fullscreenchange", () => {
        isFullscreen.value = !!document.fullscreenElement;
    });
});

// ----------------------
// MUSIC TOGGLE (hook only)
// ----------------------
function toggleMusic() {
    musicOn.value = !musicOn.value;

    // Hook this to your audio system
    console.log("Music:", musicOn.value ? "ON" : "OFF");
}

// ----------------------
// LOGOUT (Inertia example)
// ----------------------
function logout() {
    // Laravel route example
    window.location.href = "/logout";
}
</script>

<template>
    <div class="overlay">
        <div class="modal">
            <div class="header">
                <h2 class="title">⚙ Settings</h2>

                <button class="close-btn" @click="$emit('close')">Close</button>
            </div>

            <!-- SETTINGS LIST -->
            <div class="settings-list">
                <!-- MUSIC -->
                <div class="row">
                    <div class="icon">🎵</div>

                    <div class="text">
                        <p class="label">Music</p>
                        <p class="desc">Toggle background music</p>
                    </div>

                    <button class="toggle" @click="toggleMusic">
                        {{ musicOn ? "ON" : "OFF" }}
                    </button>
                </div>

                <!-- FULLSCREEN -->
                <div class="row">
                    <div class="icon">🖥</div>

                    <div class="text">
                        <p class="label">Fullscreen</p>
                        <p class="desc">Immersive gameplay mode</p>
                    </div>

                    <button class="toggle" @click="toggleFullscreen">
                        {{ isFullscreen ? "EXIT" : "ENTER" }}
                    </button>
                </div>

                <!-- LOGOUT -->
                <div class="row danger">
                    <div class="icon">🚪</div>

                    <div class="text">
                        <p class="label">Account</p>
                        <p class="desc">Leave the world safely</p>
                    </div>

                    <button class="logout" @click="logout">LOGOUT</button>
                </div>
            </div>

            <!-- FOOTER TEXT -->
            <div class="footer">
                Changes are applied instantly to your adventure.
            </div>
        </div>
    </div>
</template>

<style scoped>
.overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(6px);

    display: flex;
    align-items: center;
    justify-content: center;

    z-index: 999999;
}

/* MAIN MODAL */
.modal {
    width: 420px;

    background: #111827;
    border: 1px solid rgba(255, 255, 255, 0.08);

    border-radius: 24px;
    padding: 20px;

    box-shadow:
        0 0 40px rgba(0, 0, 0, 0.6),
        inset 0 0 0 1px rgba(255, 255, 255, 0.03);

    color: white;
}

/* HEADER */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;

    margin-bottom: 15px;
}

.title {
    font-size: 20px;
    font-weight: bold;
    letter-spacing: 1px;
    color: #f3f4f6;
}

.close-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);

    color: white;
    padding: 6px 10px;
    border-radius: 10px;

    cursor: pointer;
}

.close-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* SETTINGS LIST */
.settings-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* ROW ITEM */
.row {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 12px;
    border-radius: 14px;

    background: rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.06);

    transition: 0.2s ease;
}

.row:hover {
    background: rgba(255, 140, 0, 0.05);
    border-color: rgba(255, 140, 0, 0.2);
}

/* ICON */
.icon {
    width: 38px;
    height: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;

    font-size: 18px;
}

/* TEXT */
.text {
    flex: 1;
    margin-left: 10px;
}

.label {
    font-size: 14px;
    font-weight: 600;
}

.desc {
    font-size: 11px;
    color: rgba(255, 255, 255, 0.5);
}

/* BUTTONS */
.toggle {
    background: rgba(255, 140, 0, 0.1);
    border: 1px solid rgba(255, 140, 0, 0.3);
    color: #fbbf24;

    padding: 6px 12px;
    border-radius: 10px;

    cursor: pointer;
}

.toggle:hover {
    background: rgba(255, 140, 0, 0.2);
}

/* LOGOUT */
.danger .logout {
    background: rgba(220, 38, 38, 0.15);
    border: 1px solid rgba(220, 38, 38, 0.4);

    color: #f87171;

    padding: 6px 12px;
    border-radius: 10px;

    cursor: pointer;
}

.danger .logout:hover {
    background: rgba(220, 38, 38, 0.25);
}

/* FOOTER */
.footer {
    margin-top: 12px;
    font-size: 11px;
    color: rgba(255, 255, 255, 0.4);
    text-align: center;
}
</style>
