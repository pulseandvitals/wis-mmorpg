<script setup>
import { computed, onMounted, ref } from "vue";
import { pushAlert } from "@/Stores/GlobalAlert";
const armors = ref([]);
const materials = ref({});
const loading = ref(false);
const selectedGear = ref(null);
/**
 * FETCH ARMORS
 */
async function getArmors() {
    const resp = await axios.get("/get-armors");
    armors.value = resp.data.armors || [];
}

/**
 * FETCH MATERIALS
 */
async function getCraftingMaterials() {
    const resp = await axios.get("/get-crafting-materials");

    let data = resp.data || [];

    // ALWAYS normalize to object map
    const map = {};

    data.forEach((row) => {
        const level = Number(row.requirement_level);
        map[`level_${level}`] = {
            materials: row.materials || [],
        };
    });

    materials.value = map;
}

/**
 * SAFE LEVEL EXTRACTOR
 */
function getLevel(gear) {
    return Number(gear.requirement_level ?? gear.level ?? 0);
}

/**
 * CRAFT KEY (MATCH BACKEND)
 */
function getCraftKey(gear) {
    return `level_${getLevel(gear)}`;
}

/**
 * GROUP + ATTACH MATERIALS
 */
const groupedSets = computed(() => {
    const groups = {};

    armors.value.forEach((gear) => {
        const setName = gear.name.split(" ")[0];

        if (!groups[setName]) {
            groups[setName] = {
                name: setName,
                items: [],
                materials: [],
            };
        }

        groups[setName].items.push(gear);
    });

    Object.values(groups).forEach((group) => {
        const sample = group.items[0];
        if (!sample) return;

        const key = getCraftKey(sample);
        group.materials = materials.value?.[key]?.materials || [];
    });

    return groups;
});

async function craftGear(gear) {
    try {
        // optional: show loading state
        loading.value = true;
        pushAlert("Crafting gear...", "info");

        const res = await axios.post("/craft-gear", {
            gear: gear,
        });

        // success callback
        pushAlert(res.data.message || "Gear crafted successfully!", "success");
        loading.value = false;
        return res.data;
    } catch (error) {
        // error callback
        pushAlert(
            error.response?.data?.message || "Failed to craft gear.",
            "error",
        );
        loading.value = false;

        console.error("Craft Gear Error:", error);
    }
}
/**
 * INIT
 */
onMounted(async () => {
    await getArmors();
    await getCraftingMaterials();
});
</script>
<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl text-white font-bold tracking-wide">
                    Armory House
                </h1>

                <button
                    @click="$emit('close')"
                    class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-white transition"
                >
                    Close
                </button>
            </div>

            <!-- ARMORY LIST -->
            <div class="space-y-[4px]">
                <!-- SET GROUP -->
                <div
                    v-for="group in groupedSets"
                    :key="group.name"
                    class="rounded-md border border-white/10 bg-black/20 p-[4px]"
                >
                    <!-- HEADER -->
                    <div class="flex items-center gap-[4px] mb-[4px] px-[2px]">
                        <div
                            class="w-6 h-6 rounded bg-blue-500/10 border border-blue-400/30 flex items-center justify-center text-[10px]"
                        >
                            🛡️
                        </div>

                        <p class="text-[12px] text-white font-bold">
                            {{ group.name }} Set
                        </p>
                        <p class="text-[12px] text-orange-500">
                            (With chance for extra stats)
                        </p>
                    </div>

                    <!-- GRID -->
                    <div class="grid grid-cols-12 gap-[3px] px-[2px] pb-[2px]">
                        <div
                            v-for="gear in group.items"
                            :key="gear.id"
                            class="relative cursor-pointer active:scale-95 transition"
                            @click="selectedGear = { gear, group }"
                        >
                            <img
                                :src="`/gears/${gear.name}.png`"
                                class="w-10 h-10 object-contain"
                            />
                        </div>
                        <!-- GEAR POPUP (ONLY ONCE) -->
                        <div
                            v-if="selectedGear"
                            class="fixed inset-0 flex items-center justify-center p-4 z-[9999]"
                            @click.self="selectedGear = null"
                        >
                            <div
                                class="w-full max-w-sm bg-black/95 border border-white/10 rounded-xl p-4"
                            >
                                <!-- HEADER -->
                                <div class="mb-3 flex items-center gap-3">
                                    <!-- GEAR IMAGE -->
                                    <img
                                        :src="`/gears/${selectedGear.gear.name}.png`"
                                        class="w-10 h-10 object-contain rounded"
                                    />

                                    <!-- TEXT INFO -->
                                    <div>
                                        <p class="text-white text-sm font-bold">
                                            {{ selectedGear.gear.name }}
                                        </p>

                                        <p class="text-xs text-gray-400">
                                            Lv
                                            {{
                                                selectedGear.gear
                                                    .requirement_level
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <!-- STATS -->
                                <div class="text-xs text-gray-300 space-y-1">
                                    <p
                                        v-if="
                                            selectedGear.gear.basic_stats
                                                ?.attack
                                        "
                                    >
                                        ⚔ Attack:
                                        {{
                                            selectedGear.gear.basic_stats.attack
                                        }}
                                    </p>

                                    <p
                                        v-if="
                                            selectedGear.gear.basic_stats
                                                ?.defense
                                        "
                                    >
                                        🛡 Defense:
                                        {{
                                            selectedGear.gear.basic_stats
                                                .defense
                                        }}
                                    </p>

                                    <p v-if="selectedGear.gear.basic_stats?.hp">
                                        ❤️ HP:
                                        {{ selectedGear.gear.basic_stats.hp }}
                                    </p>

                                    <p
                                        v-if="
                                            selectedGear.gear.basic_stats
                                                ?.crit_rate
                                        "
                                    >
                                        💥 Crit Rate:
                                        {{
                                            selectedGear.gear.basic_stats
                                                .crit_rate
                                        }}%
                                    </p>

                                    <p
                                        v-if="
                                            selectedGear.gear.basic_stats?.speed
                                        "
                                    >
                                        ⚡ Speed:
                                        {{
                                            selectedGear.gear.basic_stats.speed
                                        }}
                                    </p>

                                    <p
                                        v-if="
                                            selectedGear.gear.basic_stats
                                                ?.evasion
                                        "
                                    >
                                        ⚔ Evasion:
                                        {{
                                            selectedGear.gear.basic_stats
                                                .evasion
                                        }}
                                    </p>

                                    <!-- MATERIALS -->
                                    <div class="mt-3">
                                        <p class="text-yellow-500 mb-2">
                                            Materials:
                                        </p>

                                        <div class="flex flex-wrap gap-2">
                                            <div
                                                v-for="mat in selectedGear.group
                                                    .materials"
                                                :key="mat.item"
                                                class="flex flex-col items-center text-xs text-gray-300"
                                            >
                                                <img
                                                    :src="`/materials/${mat.item}.png`"
                                                    class="w-8 h-8 object-contain"
                                                />

                                                <span
                                                    class="text-yellow-300 text-[10px] font-semibold"
                                                >
                                                    x{{ mat.qty }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- BUTTON -->
                                <div class="mt-4 grid grid-cols-2 gap-2">
                                    <button
                                        class="text-xs py-2 rounded bg-gray-500/10 border border-gray-400/30 text-gray-300 hover:bg-gray-500/20 transition"
                                        @click="selectedGear = null"
                                    >
                                        Close
                                    </button>

                                    <button
                                        class="text-xs py-2 rounded bg-green-500/10 border border-green-400/30 text-green-300 hover:bg-green-500/20 transition"
                                        :disabled="loading"
                                        @click="craftGear(selectedGear.gear)"
                                    >
                                        {{ loading ? "Crafting..." : "Craft" }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
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
