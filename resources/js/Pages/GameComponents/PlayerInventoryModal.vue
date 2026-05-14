<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl text-white font-bold">Inventory</h1>

                <button
                    @click="$emit('close')"
                    class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-white"
                >
                    Close
                </button>
            </div>

            <!-- INVENTORY -->
            <div class="grid grid-cols-3 gap-4">
                <!-- MATERIALS -->
                <div class="col-span-2">
                    <div
                        class="bg-gray-900 border border-gray-700 rounded-xl p-3"
                    >
                        <div class="text-sm text-gray-300 font-semibold mb-3">
                            Materials/Armory
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <div
                                v-for="item in getInventory"
                                :key="item.id"
                                class="bg-gray-800 border border-gray-700 rounded-lg p-2 hover:bg-gray-700 transition"
                            >
                                <!-- ICON -->
                                <div
                                    class="w-12 h-12 mx-auto rounded bg-gray-900 border border-gray-600 flex items-center justify-center text-xl"
                                >
                                    <img
                                        :src="`/materials/${item.item.name}.png`"
                                    />
                                </div>

                                <!-- NAME -->
                                <div
                                    class="text-[11px] text-white text-center mt-2 truncate"
                                >
                                    {{ item.item.name }}
                                </div>

                                <!-- QTY -->
                                <div
                                    class="text-[10px] text-gray-400 text-center"
                                >
                                    x{{ item.quantity }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ARMORY -->

                <!-- ARMORY -->
                <div>
                    <div
                        class="bg-gray-900 border border-gray-700 rounded-xl p-3"
                    >
                        <div class="text-sm text-gray-300 font-semibold mb-3">
                            Equipped
                        </div>

                        <!-- EQUIPMENT GRID -->
                        <div class="grid grid-cols-2 gap-2">
                            <div
                                v-for="gear in armory"
                                :key="gear.slot"
                                class="relative group"
                            >
                                <!-- SLOT BOX -->
                                <div
                                    class="bg-gray-800 border border-gray-700 hover:border-cyan-500 rounded-lg p-2 h-20 flex flex-col items-center justify-center transition cursor-pointer"
                                >
                                    <!-- ICON -->
                                    <div
                                        class="w-10 h-10 rounded bg-gray-900 border border-gray-600 flex items-center justify-center text-lg"
                                    >
                                        {{ gear.icon }}
                                    </div>

                                    <!-- SLOT -->
                                    <div
                                        class="text-[10px] text-gray-400 uppercase mt-1"
                                    >
                                        {{ gear.slot }}
                                    </div>
                                </div>

                                <!-- POPUP STATS -->
                                <div
                                    class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-44 bg-black border border-cyan-500 rounded-lg p-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition z-50 pointer-events-none"
                                >
                                    <div
                                        class="text-sm text-cyan-400 font-bold"
                                    >
                                        {{ gear.name }}
                                    </div>

                                    <div class="text-[11px] text-gray-400 mt-1">
                                        {{ gear.description }}
                                    </div>

                                    <div class="mt-2 space-y-1 text-[11px]">
                                        <div
                                            v-for="stat in gear.stats"
                                            :key="stat"
                                            class="text-green-400"
                                        >
                                            {{ stat }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
const getInventory = ref([]);
const loading = ref(false);
async function openInventory() {
    try {
        const response = await axios.get("/open-inventory");
        getInventory.value = response.data.inventory;
    } catch (error) {
        console.error(error);
    }
}
onMounted(() => {
    openInventory();
});
const materials = [
    {
        name: "Iron Ore",
        amount: 24,
        icon: "⛏️",
    },
    {
        name: "Wolf Fur",
        amount: 12,
        icon: "🧶",
    },
    {
        name: "Crystal",
        amount: 5,
        icon: "💎",
    },
    {
        name: "Herbs",
        amount: 31,
        icon: "🌿",
    },
    {
        name: "Dark Stone",
        amount: 7,
        icon: "🪨",
    },
    {
        name: "Bone Fragment",
        amount: 14,
        icon: "🦴",
    },
    {
        name: "Magic Dust",
        amount: 18,
        icon: "✨",
    },
    {
        name: "Goblin Ear",
        amount: 3,
        icon: "👂",
    },
];

const armory = [
    {
        slot: "Helmet",
        name: "Iron Helmet",
        icon: "🪖",
        description: "A sturdy helmet forged from iron.",
        stats: ["+12 DEF", "+5 HP"],
    },
    {
        slot: "Weapon",
        name: "Knight Sword",
        icon: "⚔️",
        description: "A balanced sword for knights.",
        stats: ["+35 ATK", "+5 CRIT"],
    },
    {
        slot: "Gloves",
        name: "War Gloves",
        icon: "🧤",
        description: "Gloves that increase grip strength.",
        stats: ["+6 ATK", "+3 SPD"],
    },
    {
        slot: "Shield",
        name: "Guardian Shield",
        icon: "🛡️",
        description: "Heavy shield with defensive power.",
        stats: ["+25 DEF", "+10 BLOCK"],
    },
    {
        slot: "Pants",
        name: "Steel Leggings",
        icon: "👖",
        description: "Leg armor made from steel.",
        stats: ["+15 DEF", "+4 SPD"],
    },
    {
        slot: "Accessory",
        name: "Ruby Ring",
        icon: "💍",
        description: "A magical ring infused with fire.",
        stats: ["+10 ATK", "+8 HP"],
    },
    {
        slot: "Armor",
        name: "Knight Armor",
        icon: "🥋",
        description: "Heavy chest armor for protection.",
        stats: ["+40 DEF", "+20 HP"],
    },
    {
        slot: "Boots",
        name: "Swift Boots",
        icon: "👢",
        description: "Boots that increase movement speed.",
        stats: ["+10 SPD", "+5 EVA"],
    },
];
</script>

<style scoped>
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

.npc-modal {
    position: fixed;
    inset: 0;

    background: rgba(0, 0, 0, 0.45);

    display: flex;
    align-items: center;
    justify-content: center;

    z-index: 999999999;
}
</style>
