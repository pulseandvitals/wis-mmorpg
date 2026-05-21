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
                        <!-- HEADER -->
                        <div class="text-sm text-gray-300 font-semibold mb-3">
                            Materials/Armory
                        </div>

                        <!-- INVENTORY -->
                        <div class="grid grid-cols-5 gap-2">
                            <div
                                v-for="item in paginatedInventory"
                                :key="item.id"
                                @click="openItem(item)"
                                class="relative cursor-pointer group"
                            >
                                <!-- SLOT -->
                                <div
                                    class="aspect-square bg-gradient-to-b from-gray-800 to-gray-900 border border-gray-700 rounded-lg flex items-center justify-center transition hover:border-yellow-400 hover:scale-105 hover:shadow-lg hover:shadow-yellow-500/20"
                                >
                                    <!-- ITEM IMAGE -->
                                    <img
                                        :src="`/${item.item_type}s/${item.item.name}.png`"
                                        class="w-12 h-12 object-contain drop-shadow-lg"
                                    />

                                    <!-- QTY -->
                                    <div
                                        class="absolute bottom-1 right-1 text-[10px] font-bold text-white bg-black/70 px-1 rounded"
                                        v-if="item.item_type !== 'gear'"
                                    >
                                        x{{ item.quantity }}
                                    </div>
                                </div>

                                <!-- HOVER GLOW -->
                                <div
                                    class="absolute inset-0 rounded-lg border border-yellow-400 opacity-0 group-hover:opacity-100 transition pointer-events-none"
                                ></div>
                            </div>
                        </div>

                        <!-- PAGINATION -->
                        <div
                            class="flex items-center justify-center gap-3 mt-3 text-white text-sm"
                        >
                            <button
                                class="px-3 py-1 bg-gray-800 border border-gray-600 rounded disabled:opacity-40"
                                @click="prevPage"
                                :disabled="currentPage === 1"
                            >
                                Prev
                            </button>

                            <div class="text-gray-300">
                                Page {{ currentPage }} / {{ totalPages }}
                            </div>

                            <button
                                class="px-3 py-1 bg-gray-800 border border-gray-600 rounded disabled:opacity-40"
                                @click="nextPage"
                                :disabled="currentPage === totalPages"
                            >
                                Next
                            </button>
                        </div>

                        <!-- GOLD & DIAMOND -->
                        <div class="mt-4 grid grid-cols-2 gap-2">
                            <!-- GOLD -->
                            <div
                                class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg px-3 py-2 flex items-center justify-between"
                            >
                                <div class="flex items-center gap-2">
                                    <span class="text-yellow-400 text-lg"
                                        >🪙</span
                                    >

                                    <span
                                        class="text-yellow-300 text-sm font-semibold"
                                    >
                                        Gold
                                    </span>
                                </div>

                                <span class="text-white text-sm font-bold">
                                    {{ playerCurrentGold }}
                                </span>
                            </div>

                            <!-- DIAMOND -->
                            <div
                                class="bg-cyan-500/10 border border-cyan-500/30 rounded-lg px-3 py-2 flex items-center justify-between"
                            >
                                <div class="flex items-center gap-2">
                                    <span class="text-cyan-400 text-lg"
                                        >💎</span
                                    >

                                    <span
                                        class="text-cyan-300 text-sm font-semibold"
                                    >
                                        Diamond
                                    </span>
                                </div>

                                <span class="text-white text-sm font-bold">
                                    {{ playerCurrentDiamond }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    v-if="selectedItem"
                    class="fixed inset-0 bg-black/70 flex items-center justify-center z-50"
                >
                    <div
                        class="w-[320px] bg-gradient-to-b from-gray-900 to-black border border-gray-500 rounded-xl p-4 shadow-2xl"
                    >
                        <!-- HEADER -->
                        <div class="flex items-center gap-3">
                            <div
                                class="w-16 h-16 bg-gray-800 border border-gray-700 rounded-lg flex items-center justify-center"
                            >
                                <img
                                    :src="`/${selectedItem.item_type}s/${selectedItem.item.name}.png`"
                                    class="w-12 h-12 object-contain"
                                />
                            </div>

                            <div>
                                <h2 class="text-yellow-400 font-bold text-lg">
                                    {{ selectedItem.item.name }}
                                    {{
                                        selectedItem.enhancement_level > 0
                                            ? `+${selectedItem.enhancement_level}`
                                            : ""
                                    }}
                                </h2>
                                <p
                                    class="text-gray-400 text-sm capitalize"
                                    v-if="selectedItem.item_type !== 'material'"
                                >
                                    Lvl.{{
                                        selectedItem.item?.requirement_level
                                    }}
                                </p>
                                <p class="text-gray-400 text-sm capitalize">
                                    {{ selectedItem.item_type }}
                                </p>
                            </div>
                        </div>

                        <!-- DETAILS -->
                        <div class="mt-4 space-y-2 text-sm">
                            <div
                                class="flex justify-between border-b border-gray-800 pb-1"
                                v-if="selectedItem.item.type === 'material'"
                            >
                                <span class="text-gray-400">Quantity</span>
                                <span class="text-white font-semibold">
                                    x{{ selectedItem.quantity }}
                                </span>
                            </div>

                            <!-- SAMPLE STATS -->
                            <div
                                class="flex justify-between border-b border-gray-800 pb-1"
                                v-if="selectedItem.item.type === 'material'"
                            >
                                <span class="text-gray-400">Rarity</span>
                                <span
                                    class="text-green-400 font-semibold capitalize"
                                    :class="{
                                        'text-green-400':
                                            selectedItem.item.rarity ===
                                            'common',
                                        'text-purple-400':
                                            selectedItem.item.rarity === 'rare',
                                        'text-pink-400':
                                            selectedItem.item.rarity === 'epic',
                                    }"
                                >
                                    {{ selectedItem.item.rarity }}
                                </span>
                            </div>

                            <div
                                class="flex justify-between border-b border-gray-800 pb-1"
                                v-if="selectedItem.item.type === 'material'"
                            >
                                <span class="text-gray-400">Type</span>
                                <span
                                    class="text-cyan-400 font-semibold capitalize"
                                >
                                    {{ selectedItem.item_type }}
                                </span>
                            </div>

                            <!-- STATS -->
                            <div
                                class="border-b border-gray-800 pb-2"
                                v-if="selectedItem.item_type === 'gear'"
                            >
                                <div class="text-gray-400 mb-2">Stats</div>

                                <div class="space-y-1 text-sm">
                                    <!-- BASIC STATS (GRAY / NORMAL) -->
                                    <div
                                        v-if="basicStats.attack"
                                        class="flex justify-between"
                                    >
                                        <span class="text-gray-300"
                                            >⚔ Attack</span
                                        >
                                        <span class="text-gray-200"
                                            >+{{ basicStats.attack }}</span
                                        >
                                    </div>

                                    <div
                                        v-if="basicStats.defense"
                                        class="flex justify-between"
                                    >
                                        <span class="text-gray-300"
                                            >🛡 Defense</span
                                        >
                                        <span class="text-gray-200"
                                            >+{{ basicStats.defense }}</span
                                        >
                                    </div>

                                    <div
                                        v-if="basicStats.hp"
                                        class="flex justify-between"
                                    >
                                        <span class="text-gray-300">❤️ HP</span>
                                        <span class="text-gray-200"
                                            >+{{ basicStats.hp }}</span
                                        >
                                    </div>

                                    <div
                                        v-if="basicStats.mp"
                                        class="flex justify-between"
                                    >
                                        <span class="text-gray-300">🔷 MP</span>
                                        <span class="text-gray-200"
                                            >+{{ basicStats.mp }}</span
                                        >
                                    </div>

                                    <div
                                        v-if="basicStats.speed"
                                        class="flex justify-between"
                                    >
                                        <span class="text-gray-300"
                                            >💨 Speed</span
                                        >
                                        <span class="text-gray-200"
                                            >+{{ basicStats.speed }}</span
                                        >
                                    </div>

                                    <div
                                        v-if="basicStats.crit"
                                        class="flex justify-between"
                                    >
                                        <span class="text-gray-300"
                                            >✨ Crit</span
                                        >
                                        <span class="text-gray-200"
                                            >+{{ basicStats.crit }}%</span
                                        >
                                    </div>

                                    <!-- RANDOM STATS (GREEN / BONUS) -->
                                    <div
                                        v-if="randomStats.attack"
                                        class="flex justify-between"
                                    >
                                        <span class="text-green-400"
                                            >⚔ Bonus Attack</span
                                        >
                                        <span
                                            class="text-green-300 font-semibold"
                                        >
                                            +{{ randomStats.attack }}
                                        </span>
                                    </div>

                                    <div
                                        v-if="randomStats.defense"
                                        class="flex justify-between"
                                    >
                                        <span class="text-green-400"
                                            >🛡 Bonus Defense</span
                                        >
                                        <span
                                            class="text-green-300 font-semibold"
                                        >
                                            +{{ randomStats.defense }}
                                        </span>
                                    </div>

                                    <div
                                        v-if="randomStats.hp"
                                        class="flex justify-between"
                                    >
                                        <span class="text-green-400"
                                            >❤️ Bonus HP</span
                                        >
                                        <span
                                            class="text-green-300 font-semibold"
                                        >
                                            +{{ randomStats.hp }}
                                        </span>
                                    </div>

                                    <div
                                        v-if="randomStats.mp"
                                        class="flex justify-between"
                                    >
                                        <span class="text-green-400"
                                            >🔷 Bonus MP</span
                                        >
                                        <span
                                            class="text-green-300 font-semibold"
                                        >
                                            +{{ randomStats.mp }}
                                        </span>
                                    </div>

                                    <div
                                        v-if="randomStats.speed"
                                        class="flex justify-between"
                                    >
                                        <span class="text-green-400"
                                            >💨 Bonus Speed</span
                                        >
                                        <span
                                            class="text-green-300 font-semibold"
                                        >
                                            +{{ randomStats.speed }}
                                        </span>
                                    </div>

                                    <div
                                        v-if="randomStats.crit"
                                        class="flex justify-between"
                                    >
                                        <span class="text-green-400"
                                            >✨ Bonus Crit</span
                                        >
                                        <span
                                            class="text-green-300 font-semibold"
                                        >
                                            +{{ randomStats.crit }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DESCRIPTION -->
                        <div
                            class="mt-4 bg-gray-900 border border-gray-800 rounded-lg p-3 text-xs text-gray-300"
                        >
                            {{
                                selectedItem.item.description ||
                                "A mysterious item from the world."
                            }}
                        </div>
                        <!-- ACTIONS -->
                        <div class="mt-4 flex gap-2">
                            <button
                                class="flex-1 bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 rounded-lg transition"
                                v-if="selectedItem.item_type === 'gear'"
                                @click="useGear"
                            >
                                Use
                            </button>
                            <button
                                v-else
                                class="flex-1 bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 rounded-lg transition"
                                @click="usePotion"
                            >
                                Use
                            </button>

                            <button
                                @click="closeItem"
                                class="flex-1 bg-gray-800 hover:bg-gray-700 text-white py-2 rounded-lg transition"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>

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
                                    class="bg-gray-800 border border-gray-700 hover:border-yellow-500 rounded-lg p-2 h-20 flex flex-col items-center justify-center transition-all duration-200 cursor-pointer"
                                >
                                    <!-- ICON -->
                                    <div
                                        class="w-10 h-10 rounded bg-gray-900 border border-gray-600 flex items-center justify-center overflow-hidden"
                                    >
                                        <img
                                            :src="gear.icon"
                                            :alt="gear.name"
                                            class="w-full h-full object-contain p-1"
                                        />
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
                                    class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-44 bg-black border border-yellow-500 rounded-lg p-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition z-50 pointer-events-none"
                                >
                                    <div
                                        class="text-sm text-yellow-400 font-bold"
                                    >
                                        {{ gear.name }}
                                        {{ gear.enhancement_level }}
                                    </div>

                                    <div class="text-[11px] text-gray-400 mt-1">
                                        {{ gear.description }}
                                    </div>

                                    <div class="mt-2 space-y-1 text-[11px]">
                                        <div
                                            v-for="stat in gear.stats"
                                            :key="stat"
                                            class="text-white"
                                        >
                                            {{ stat }}
                                        </div>
                                    </div>
                                    <div class="mt-2 space-y-1 text-[11px]">
                                        <div
                                            v-for="stat in gear.random_stats"
                                            :key="stat"
                                            class="text-green-400"
                                        >
                                            {{ stat }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- STATS -->
                        <div
                            class="mt-4 rounded-lg border border-gray-700 bg-gray-900/80 p-3 text-white"
                        >
                            <!-- HEADER -->
                            <div class="flex items-center justify-between mb-2">
                                <h2 class="text-xs font-semibold">
                                    Character Status
                                </h2>
                                <div class="text-xs text-gray-300">
                                    Lv. {{ props.player.current_level }}
                                </div>
                            </div>

                            <!-- STATS -->
                            <div class="grid grid-cols-2 gap-1 text-xs">
                                <div class="rounded bg-gray-800 px-2 py-1">
                                    HP: {{ props.player.current_health }} /
                                    {{ props.player.max_health }}
                                </div>

                                <div class="rounded bg-gray-800 px-2 py-1">
                                    MP: {{ props.player.current_mana }} /
                                    {{ props.player.max_mana }}
                                </div>

                                <div class="rounded bg-gray-800 px-2 py-1">
                                    ATK: {{ props.player.total_attack }}
                                </div>

                                <div class="rounded bg-gray-800 px-2 py-1">
                                    DEF: {{ props.player.total_defense }}
                                </div>

                                <div class="rounded bg-gray-800 px-2 py-1">
                                    SPD: {{ props.player.total_speed }}
                                </div>

                                <div class="rounded bg-gray-800 px-2 py-1">
                                    CRIT:
                                    {{
                                        props.player.total_critical_percentage
                                    }}%
                                </div>

                                <div class="rounded bg-gray-800 px-2 py-1">
                                    EVA:
                                    {{ props.player.total_evasion_percentage }}%
                                </div>
                                <div class="rounded bg-gray-800 px-2 py-1">
                                    Kills: 0
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
import { onMounted, ref, computed, watch } from "vue";
import { pushAlert } from "@/Stores/GlobalAlert";
const props = defineProps({
    player: Object,
});

const getInventory = ref([]);
const playerCurrentGold = props.player.current_gold;
const playerCurrentDiamond = props.player.current_diamond;
const selectedItem = ref(null);

const EQUIP_SLOTS = [
    { key: "helmet", label: "Helmet", folder: "gears" },
    { key: "weapon", label: "Weapon", folder: "gears" },
    { key: "armor", label: "Armor", folder: "gears" },
    { key: "boots", label: "Boots", folder: "gears" },
    { key: "gloves", label: "Gloves", folder: "gears" },
    { key: "shield", label: "Shield", folder: "gears" },
    { key: "pants", label: "Pants", folder: "gears" },
    { key: "ring", label: "Ring", folder: "gears" },
];

const armory = computed(() =>
    EQUIP_SLOTS.map((s) => {
        const equip = props.player?.[s.key];

        return {
            slot: s.label,
            icon: equip ? `/${s.folder}/${equip.gear.name}.png` : "❔",
            name: equip?.gear.name || `Empty ${s.label} Slot`,
            description: equip?.gear.requirement_level
                ? `Level ${equip.gear.requirement_level} ${s.label}`
                : `No ${s.label.toLowerCase()} equipped`,
            stats: equip?.gear?.basic_stats
                ? Object.entries(JSON.parse(equip?.gear?.basic_stats)).map(
                      ([k, v]) => `+${v} ${k.toUpperCase()}`,
                  )
                : [],
            random_stats: equip?.random_stat
                ? Object.entries(JSON.parse(equip.random_stat)).map(
                      ([k, v]) => `+${v} ${k.toUpperCase()}`,
                  )
                : [],
            enhancement_level: equip?.enhancement_level
                ? `+${equip.enhancement_level}`
                : "",
        };
    }),
);

async function usePotion() {
    try {
        const res = await axios.post("/use-potion", {
            potion: selectedItem.value,
        });
        Object.assign(props.player, res.data.player);
        pushAlert(res.data.message || "Potion used successfully!", "success");
        openInventory();
        closeItem();
    } catch (error) {
        pushAlert(
            error.response?.data?.message || "Failed to use potion.",
            "error",
        );
        console.error("Use Potion Error:", error);
    }
}

function openItem(item) {
    selectedItem.value = item;
    console.log("Selected Item:", item);
}

function closeItem() {
    selectedItem.value = null;
}

const basicStats = computed(() => {
    try {
        return selectedItem.value.item?.basic_stats || "{}";
    } catch {
        return {};
    }
});

const randomStats = computed(() => {
    try {
        return JSON.parse(selectedItem.value?.random_stat || "{}");
    } catch {
        return {};
    }
});

async function useGear() {
    try {
        const res = await axios.post("/use-gear", {
            gear: selectedItem.value,
        });

        // backend might still send "error" inside 200
        Object.assign(props.player, res.data.player);
        pushAlert(res.data.message, "success");
        openInventory();
        closeItem();
    } catch (error) {
        pushAlert(
            error?.response?.data?.message || "Something went wrong",
            "error",
        );
        closeItem();
    }
}

async function openInventory() {
    try {
        const response = await axios.get("/open-inventory");
        getInventory.value = response.data.inventory;
    } catch (error) {
        console.error(error);
    }
}

const currentPage = ref(1);
const perPage = 15; // 4 x 4 grid

const paginatedInventory = computed(() => {
    const items = getInventory.value ?? getInventory;

    const start = (currentPage.value - 1) * perPage;
    const end = start + perPage;

    return items.slice(start, end);
});

const totalPages = computed(() => {
    const items = getInventory.value ?? getInventory;
    return Math.ceil(items.length / perPage);
});

function nextPage() {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
}

function prevPage() {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
}

onMounted(() => {
    openInventory();
});

watch(
    () => paginatedInventory.value.length,
    (newLength, oldLength) => {
        if (newLength > 0 && newLength !== oldLength) {
            openInventory();
        }
    },
);
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
