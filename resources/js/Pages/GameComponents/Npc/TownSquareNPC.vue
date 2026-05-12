<script setup>
import { ref, computed } from "vue";

const open = ref(false);

const currentNpc = ref(null);

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
};

const npc = computed(() => npcData[currentNpc.value]);

function openNpc(key) {
    currentNpc.value = key;
    open.value = true;
}

function closeNpc() {
    open.value = false;
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

        <!-- MODAL -->
        <div v-if="open && npc" class="npc-modal">
            <div class="npc-modal-content p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl text-white font-bold">
                        {{ npc.name }}
                    </h1>

                    <button
                        @click="closeNpc"
                        class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-white"
                    >
                        Close
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button
                        v-for="service in npc.services"
                        :key="service"
                        class="p-5 rounded-xl bg-gray-800 hover:bg-gray-700 text-white transition"
                    >
                        {{ service }}
                    </button>
                </div>
            </div>
        </div>
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

/* NPC MODAL */
.npc-modal {
    position: fixed;
    inset: 0;

    background: rgba(0, 0, 0, 0.45);

    display: flex;
    align-items: center;
    justify-content: center;

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
