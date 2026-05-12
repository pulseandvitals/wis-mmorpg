<script setup>
import { ref, computed } from "vue";
import DungeonMaps from "./DungeonMaps.vue";
const props = defineProps({
    all_maps: Object,
});

const isPortalOpen = ref(false);
const isBlacksmithOpen = ref(false);
const isPotionHouseOpen = ref(false);
const isWeaponHouseOpen = ref(false);
const isArmorHouseOpen = ref(false);
const isEventOrgOpen = ref(false);
const isMarketOpen = ref(false);
const isGeneralStoreOpen = ref(false);
const currentNpc = ref();
const npcData = {
    blacksmith: {
        name: "Blacksmith",
        x: 340,
        y: 100,
        icon: "⚒️",
        color: "from-stone-700 to-stone-900",
        services: ["Forge Weapons", "Upgrade Equipment", "Repair Armor"],
    },

    potion: {
        name: "Potion House",
        x: 990,
        y: 75,
        icon: "🧪",
        color: "from-emerald-700 to-green-900",
        services: ["Buy Potions", "Mana Elixirs", "Revive Scrolls"],
    },

    weapon: {
        name: "Weapon House",
        x: 680,
        y: 10,
        icon: "🗡️",
        color: "from-red-700 to-red-950",
        services: ["Buy Weapons", "Rare Blades", "Legendary Shop"],
    },

    armor: {
        name: "Armor House",
        x: 180,
        y: 455,
        icon: "🛡️",
        color: "from-slate-700 to-slate-900",
        services: ["Heavy Armor", "Light Armor", "Magic Robes"],
    },

    event: {
        name: "Event Org",
        x: 1165,
        y: 235,
        icon: "✨",
        color: "from-purple-700 to-indigo-950",
        services: ["Learn Skills", "Magic Scrolls", "Enchant Gear"],
    },
    market: {
        name: "Market/Mall",
        x: 600,
        y: 550,
        icon: "✨",
        color: "from-purple-700 to-indigo-950",
        services: ["Learn Skills", "Magic Scrolls", "Enchant Gear"],
    },

    general: {
        name: "General Store",
        x: 1260,
        y: 450,
        icon: "✨",
        color: "from-purple-700 to-indigo-950",
        services: ["Learn Skills", "Magic Scrolls", "Enchant Gear"],
    },
    portal: {
        name: "Dungeon Portal",
        x: 1260,
        y: 750,
        icon: "✨",
        color: "from-purple-700 to-indigo-950",
        services: isPortalOpen,
    },
};
const npc = computed(() => npcData[currentNpc.value]);
function openNpc(key) {
    currentNpc.value = key;
    switch (key) {
        case "portal":
            isPortalOpen.value = true;
            break;

        case "blacksmith":
            isBlacksmithOpen.value = true;
            break;

        case "potion":
            isPotionHouseOpen.value = true;
            break;

        case "weapon":
            isWeaponHouseOpen.value = true;
            break;

        case "armor":
            isArmorHouseOpen.value = true;
            break;

        case "event":
            isEventOrgOpen.value = true;
            break;

        case "market":
            isMarketOpen.value = true;
            break;

        case "general":
            isGeneralStoreOpen.value = true;
            break;

        default:
            break;
    }
}
</script>

<template>
    <div class="relative w-full h-screen overflow-hidden">
        <!-- NPCS -->
        <button
            v-for="(data, key) in npcData"
            :key="key"
            @click="openNpc(key)"
            class="absolute px-6 py-2 bg-gray-800 hover:bg-gray-700 text-white text-xs rounded-lg border border-gray-600"
            :style="{
                left: data.x + 'px',
                top: data.y + 'px',
            }"
        >
            {{ data.icon }} {{ data.name }}
        </button>
        {{ isPortalOpen }}
        <DungeonMaps
            v-if="isPortalOpen"
            :all_maps="all_maps"
            :npc="npc"
            @close="isPortalOpen = false"
        />
    </div>
</template>
<style scoped>
.npc-wrapper {
    position: fixed;
    inset: 0;

    z-index: 999999999;
}

/* BUTTON AREA */
.npc-wrapper > div {
}

/* MODAL BOX */
.npc-modal-content {
    width: 100%;
    max-width: 900px;
    min-height: 400px;

    background: #111827;

    border-radius: 24px;

    border: 1px solid rgba(255, 255, 255, 0.1);

    position: relative;

    z-index: 999999999;

    pointer-events: auto;
}

/* FORCE BUTTONS CLICKABLE */
button {
    pointer-events: auto !important;
}
</style>
