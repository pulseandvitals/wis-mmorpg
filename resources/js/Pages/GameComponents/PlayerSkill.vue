<script setup>
const props = defineProps({
    playerSkills: Object,
});
</script>
<template>
    <div class="hud-bottom">
        <div
            class="skill"
            v-for="(skill, index) in playerSkills"
            :key="skill.id"
        >
            <img :src="skill.icon_path" :alt="skill.name" class="skill-icon" />

            <span class="skill-key">
                {{ index + 1 }}
            </span>
        </div>
    </div>
</template>
<style scoped>
/* SKILL BAR */
.hud-bottom {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);

    display: flex;
    gap: 10px;

    z-index: 999;
}

.skill {
    width: 50px;
    height: 50px;

    background: rgba(0, 0, 0, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;

    display: flex;
    justify-content: center;
    align-items: center;

    color: white;
    font-weight: bold;

    cursor: pointer;
}

.skill:hover {
    background: rgba(255, 255, 255, 0.1);
}
</style>
