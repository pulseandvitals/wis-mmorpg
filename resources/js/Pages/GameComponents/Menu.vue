<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import PlayerSkillModal from "./PlayerSkillModal.vue";
import PlayerInventoryModal from "./PlayerInventoryModal.vue";

const props = defineProps({
    classSkills: Object,
    all_maps: Object,
});

const form = useForm({});
const isSkillOpen = ref(false);
const isInventoryOpen = ref(false);

const mapId = props.all_maps.find((map) => map.name === "Town Square")?.map_id;

const menuItems = [
    { id: 1, label: "Inventory", icon: "🎒" },
    { id: 2, label: "Party", icon: "✨" },
    { id: 3, label: "Skills", icon: "✨" },
    { id: 4, label: "Ranking", icon: "🏆" },
    { id: 5, label: "Discord", icon: "💬" },
    { id: 6, label: "Town Square", icon: "💬" },
];

const handleMenuClick = (item) => {
    if (item.id === 1) {
        isInventoryOpen.value = true;
    }
    if (item.id === 3) {
        isSkillOpen.value = true;
    }
    if (item.id === 5) {
        window.open("https://discord.com", "_blank");
    }
    if (item.id === 6) {
        form.get(route("world.map", mapId));
    }
};
</script>
<template>
    <div class="hud-menu">
        <button
            v-for="item in menuItems"
            :key="item.id"
            class="hud-btn"
            @click="handleMenuClick(item)"
        >
            <span class="hud-icon">
                {{ item.icon }}
            </span>

            <span class="hud-label">
                {{ item.label }}
            </span>
        </button>
    </div>

    <PlayerInventoryModal
        v-if="isInventoryOpen"
        @close="isInventoryOpen = false"
    />
    <PlayerSkillModal
        :classSkills="classSkills"
        v-if="isSkillOpen"
        @close="isSkillOpen = false"
    />
</template>
<style scoped>
.hud-menu {
    position: absolute;
    top: 12px;
    right: 12px;

    display: flex;
    flex-direction: column;
    gap: 6px;

    z-index: 999;
}

.hud-btn {
    display: flex;
    align-items: center;
    gap: 6px;

    padding: 6px 10px;

    background: rgba(0, 0, 0, 0.55);
    backdrop-filter: blur(6px);

    border: 1px solid rgba(251, 191, 36, 0.4);
    border-radius: 6px;

    color: #fbbf24;

    font-size: 15px;
    font-family: "Nova Square", sans-serif;

    cursor: pointer;

    transition: all 0.15s ease;
}

.hud-btn:hover {
    background: rgba(251, 191, 36, 0.15);
    transform: translateX(-2px);
}

.hud-icon {
    font-size: 15px;
}

.hud-label {
    white-space: nowrap;
}
</style>
