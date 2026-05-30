<script setup>
import { reactive } from "vue";
const props = defineProps({
    player: {
        type: Object,
        required: true,
    },
});
</script>
<template>
    <div class="hud-top">
        <div class="hud-panel">
            <!-- HEADER -->
            <div class="player-header">
                <div class="player-name">
                    {{ player.name }}
                    <span class="lvl">Lv {{ player.current_level }}</span>
                </div>

                <div class="player-class">
                    {{ player.class_type }}
                </div>
            </div>

            <!-- HP -->
            <div class="stat-row">
                <span class="label hp">HP</span>

                <div class="bar">
                    <div
                        class="fill hp-fill"
                        :style="{
                            width:
                                (player.current_health / player.max_health) *
                                    100 +
                                '%',
                        }"
                    ></div>
                </div>

                <span class="value">
                    {{ player.current_health }}/{{ player.max_health }}
                </span>
            </div>

            <!-- MP -->
            <div class="stat-row">
                <span class="label mp">MP</span>

                <div class="bar">
                    <div
                        class="fill mp-fill"
                        :style="{
                            width:
                                (player.current_mana / player.max_mana) * 100 +
                                '%',
                        }"
                    ></div>
                </div>

                <span class="value">
                    {{ player.current_mana }}/{{ player.max_mana }}
                </span>
            </div>

            <!-- EXP -->
            <div class="stat-row">
                <span class="label exp">EXP</span>

                <div class="bar">
                    <div
                        class="fill exp-fill"
                        :style="{
                            width:
                                Math.min(player.experience_percentage, 100) +
                                '%',
                        }"
                    ></div>
                </div>

                <span class="value"> {{ player.experience_percentage }}% </span>
            </div>
        </div>
    </div>
</template>
<style scoped>
.hud-top {
    position: absolute;
}
.hud-panel {
    width: 330px; /* increased from 220 */
    padding: 15px; /* slightly more breathing room */
    background: rgba(0, 0, 0, 0.55);
    margin: 5px;
    color: white;
    font-family: sans-serif;
    font-size: 11px; /* slightly bigger base font */
}

/* HEADER */
.player-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.player-name {
    font-size: 13px;
    font-weight: bold;
}

.lvl {
    font-size: 10px;
    opacity: 0.85;
    margin-left: 5px;
}

.player-class {
    font-size: 10px;
    opacity: 0.75;
}

/* ROW */
.stat-row {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 5px;
}

/* LABEL */
.label {
    width: 28px;
    font-size: 10px;
    opacity: 0.85;
}

/* BAR */
.bar {
    flex: 1;
    height: 7px; /* slightly thicker */
    background: rgba(255, 255, 255, 0.12);
    border-radius: 4px;
    overflow: hidden;
}

.fill {
    height: 100%;
    transition: width 0.25s ease;
}

/* COLORS */
.hp-fill {
    background: #22c55e;
}
.mp-fill {
    background: #3b82f6;
}
.exp-fill {
    background: #facc15;
}

/* VALUE */
.value {
    width: 70px; /* more space for numbers */
    text-align: right;
    font-size: 10px;
    opacity: 0.9;
}
</style>
