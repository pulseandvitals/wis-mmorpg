<script setup>
import { reactive } from "vue";
const props = defineProps({
    playerData: {
        type: Object,
        required: true,
    },
});
</script>
<template>
    <div class="hud-top">
        <div class="hud-panel">
            <div class="player-header">
                <div class="player-name">{{ playerData.name }}</div>

                <div class="player-meta">
                    <span class="lvl">Lv. {{ playerData.current_level }}</span>
                    <span class="dot">•</span>
                    <span class="class">{{ playerData.class_type }}</span>
                </div>
            </div>
            <!-- HP -->
            <div class="bar-row">
                <div class="label hp-label">HP</div>

                <div class="bar hp">
                    <div
                        class="fill"
                        :style="{
                            width:
                                (playerData.current_health /
                                    playerData.max_health) *
                                    100 +
                                '%',
                        }"
                    ></div>
                    <span
                        >{{ playerData.current_health }} /
                        {{ playerData.max_health }}</span
                    >
                </div>
            </div>

            <!-- MP -->
            <div class="bar-row">
                <div class="label mp-label">MP</div>

                <div class="bar mp">
                    <div
                        class="fill"
                        :style="{
                            width:
                                (playerData.current_mana /
                                    playerData.max_mana) *
                                    100 +
                                '%',
                        }"
                    ></div>
                    <span
                        >{{ playerData.current_mana }} /
                        {{ playerData.max_mana }}</span
                    >
                </div>
            </div>

            <!-- EXP -->
            <div class="bar-row">
                <div class="label exp-label">EXP</div>

                <div class="bar exp">
                    <div
                        class="fill"
                        :style="{
                            width:
                                (playerData.current_experience /
                                    playerData.max_experience) *
                                    100 +
                                '%',
                        }"
                    ></div>
                    <span>{{ playerData.current_experience }}%</span>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.hud-top {
    position: absolute;
    top: 5px;
    left: 5px;

    z-index: 999;
}

.hud-panel {
    width: 280px;

    background: rgba(0, 0, 0, 0.5);

    border: 3px solid #374151;
    border-radius: 10px;

    padding: 8px;

    display: flex;
    flex-direction: column;
    gap: 5px;

    backdrop-filter: blur(4px);
}

/* ROW */
.bar-row {
    display: flex;
    align-items: center;
    gap: 5px;
}

/* LABEL (same vibe as chat header text) */
.label {
    width: 32px;

    font-size: 8px;
    color: #f1f1f1;

    text-align: right;
}

/* BAR BASE (MATCH CHAT STYLE) */
.bar {
    position: relative;

    flex: 1;

    height: 14px;

    background: rgba(0, 0, 0, 0.5);

    border: 1px solid rgba(255, 255, 255, 0.15);

    border-radius: 6px;

    overflow: hidden;
}

/* TEXT INSIDE BAR */
.bar span {
    position: absolute;

    width: 100%;
    height: 100%;

    display: flex;
    justify-content: center;
    align-items: center;

    font-size: 9px;
    color: white;

    z-index: 2;
}

/* FILL */
.fill {
    height: 100%;

    transition: width 0.25s linear;
}

/* COLORS (same soft MMO style as chat UI accents) */
.hp .fill {
    background: #c0392b; /* solid red */
}

.mp .fill {
    background: #2980b9; /* solid blue */
}

.exp .fill {
    background: #f1c40f; /* solid gold */
}

/* OPTIONAL: subtle glow like chat panel */
.hud-panel {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}
/* HEADER SECTION */
.player-header {
    padding-bottom: 6px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 4px;
}

.player-name {
    color: #facc15;
    font-size: 9px;
    letter-spacing: 1px;
}

.player-meta {
    font-size: 9px;
    color: #d1d5db;

    display: flex;
    align-items: center;
    gap: 4px;
}

.lvl {
    color: #f1f1f1;
}

.class {
    color: #f1f1f1;
}

.dot {
    opacity: 0.5;
}
</style>
