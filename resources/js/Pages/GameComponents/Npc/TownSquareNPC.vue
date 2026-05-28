<script setup>
import { ref, computed, proxyRefs } from "vue";
import DungeonMaps from "./DungeonMaps.vue";
import Healer from "./Healer.vue";
import BlackSmith from "./BlackSmith.vue";
import WeaponHouse from "./WeaponHouse.vue";
import ArmorHouse from "./ArmorHouse.vue";
import Portal from "../Portal.vue";
import EventList from "../Event/EventList.vue";
import PotionHouse from "./PotionHouse.vue";
import { router } from "@inertiajs/vue3";
import { pushAlert } from "@/Stores/GlobalAlert";
import Market from "./Market.vue";
import CardInformation from "./CardInformation.vue";
import PvPMaster from "./PvPMaster.vue";
import Guild from "./Guild.vue";
const props = defineProps({
    all_maps: Object,
    player: Object,
    current_map: Object,
});

const isPortalOpen = ref(false);
const isBlacksmithOpen = ref(false);
const isPotionHouseOpen = ref(false);
const isWeaponHouseOpen = ref(false);
const isArmorHouseOpen = ref(false);
const isEventOrgOpen = ref(false);
const isMarketOpen = ref(false);
const isGeneralStoreOpen = ref(false);
const isHealerOpen = ref(false);
const isCardOpen = ref(false);
const currentNpc = ref();
const isPvpOpen = ref(false);
const isGuildOpen = ref(false);
const wisteriaVillage = props.all_maps.find(
    (map) => map.name === "Wisteria Village",
);
const wisteriaTown = props.all_maps.find((map) => map.name === "Wisteria Town");
const npcData = {
    blacksmith: {
        name: "Blacksmith",
        x: 360,
        y: 100,
        icon: "⚒️",
        color: "from-stone-700 to-stone-900",
        services: ["Forge Weapons", "Upgrade Equipment", "Repair Armor"],
    },

    potion: {
        name: "Potion/Exp House",
        x: 1010,
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
        x: 1160,
        y: 230,
        icon: "🎁",
        color: "from-purple-700 to-indigo-950",
        services: ["Learn Skills", "Magic Scrolls", "Enchant Gear"],
    },
    market: {
        name: "Market/Mall",
        x: 605,
        y: 550,
        icon: "💸",
        color: "from-purple-700 to-indigo-950",
        services: ["Learn Skills", "Magic Scrolls", "Enchant Gear"],
    },

    card: {
        name: "Card Collection",
        x: 1265,
        y: 450,
        icon: "🃏",
        color: "from-purple-700 to-indigo-950",
        services: ["Learn Skills", "Magic Scrolls", "Enchant Gear"],
    },
    healer: {
        name: "Healer",
        x: 770,
        y: 300,
        icon: "✨",
        color: "from-purple-700 to-indigo-950",
        services: isHealerOpen,
    },
};
const npc = computed(() => npcData[currentNpc.value]);
function openNpc(key) {
    currentNpc.value = key;
    switch (key) {
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

        case "card":
            isCardOpen.value = true;
            break;
        case "healer":
            isHealerOpen.value = true;
            break;
        default:
            break;
    }
}

function handleOpenForDungeonMaps() {
    isPortalOpen.value = true;
}

function handleOpenForWisteriaVillage() {
    router.visit(route("world.map", wisteriaVillage.map_id), {
        onStart: () => pushAlert("Entering Wisteria Village...", "success"),
        onFinish: () => pushAlert("Welcome to Wisteria Village!", "success"),
    });
}

function handleOpenForWisteriaTown() {
    router.visit(route("world.map", wisteriaTown.map_id), {
        onStart: () => pushAlert("Entering Wisteria Town...", "success"),
        onFinish: () => pushAlert("Welcome to Wisteria Town!", "success"),
    });
}
function handleOpenPvpArena() {
    isPvpOpen.value = true;
}
</script>

<template>
    <template v-if="wisteriaTown.map_id === current_map.map_id">
        <!-- NPCS -->
        <button
            v-for="(data, key) in npcData"
            :key="key"
            @click="openNpc(key)"
            class="absolute px-12 py-2 text-white text-xs font-semibold rounded-xl border border-cyan-300/30 bg-gradient-to-br from-cyan-400/30 via-blue-500/20 to-purple-600/30 backdrop-blur-md shadow-[0_0_15px_rgba(34,211,238,0.35)] hover:scale-[1.35] hover:shadow-[0_0_25px_rgba(34,211,238,0.6)] transition-all duration-200 overflow-hidden"
            :style="{
                left: data.x + 'px',
                top: data.y + 'px',
                transform: 'translateX(-15%)', // keeps alignment nice when widened
            }"
        >
            <span class="relative z-10 drop-shadow-md whitespace-nowrap">
                {{ data.icon }} {{ data.name }}
            </span>
        </button>

        <Portal :x="1370" :y="790" @open="handleOpenForDungeonMaps" />
        <Portal :x="1330" :y="160" @open="handleOpenForWisteriaVillage" />
        <BlackSmith v-if="isBlacksmithOpen" @close="isBlacksmithOpen = false" />
        <WeaponHouse
            v-if="isWeaponHouseOpen"
            @close="isWeaponHouseOpen = false"
        />
        <Market v-if="isMarketOpen" @close="isMarketOpen = false" />
        <ArmorHouse v-if="isArmorHouseOpen" @close="isArmorHouseOpen = false" />
        <EventList v-if="isEventOrgOpen" @close="isEventOrgOpen = false" />
        <CardInformation v-if="isCardOpen" @close="isCardOpen = false" />
        <PotionHouse
            v-if="isPotionHouseOpen"
            @close="isPotionHouseOpen = false"
        />
        <DungeonMaps
            v-if="isPortalOpen"
            :all_maps="all_maps"
            :npc="npc"
            @close="isPortalOpen = false"
        />
        <Healer
            v-if="isHealerOpen"
            :player="player"
            :npc="npc"
            @close="isHealerOpen = false"
        />
    </template>
    <template v-if="wisteriaVillage.map_id === current_map.map_id">
        <Portal :x="480" :y="690" @open="handleOpenForWisteriaTown" />
        <Portal
            :x="760"
            :y="110"
            :is_danger="true"
            @open="handleOpenPvpArena"
        />

        <PvPMaster
            v-if="isPvpOpen"
            :all_maps="all_maps"
            :player="player"
            @close="isPvpOpen = false"
        />
        <Guild />
    </template>
</template>
<style scoped>
.npc-wrapper {
    position: fixed;
    inset: 0;

    z-index: 999999999;
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
