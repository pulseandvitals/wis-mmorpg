<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, onMounted, ref } from "vue";

const armors = ref([]);
async function getArmors() {
    let resp = await axios.get("/get-armors");
    armors.value = resp.data.armors;
}

const groupedSets = computed(() => {
    const groups = {};

    armors.value.forEach((gear) => {
        // "Ashen Helmet" => "Ashen"
        const setName = gear.name.split(" ")[0];

        if (!groups[setName]) {
            groups[setName] = [];
        }

        groups[setName].push(gear);
    });

    return groups;
});

onMounted(() => {
    getArmors();
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
                    v-for="(setItems, setName) in groupedSets"
                    :key="setName"
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
                            {{ setName }} Set
                        </p>
                    </div>

                    <!-- GRID -->
                    <div class="grid grid-cols-12 gap-[3px] px-[2px] pb-[2px]">
                        <div
                            v-for="gear in setItems"
                            :key="gear.id"
                            class="relative group w-11 h-11 rounded-md border border-white/10 bg-black/40 flex items-center justify-center cursor-pointer hover:bg-blue-500/10"
                        >
                            <!-- ICON (UNCHANGED SIZE) -->
                            <img
                                :src="`/gears/${gear.name}.png`"
                                class="w-10 h-10 object-contain"
                            />

                            <!-- TOOLTIP -->
                            <div
                                class="absolute left-full ml-2 top-1/2 -translate-y-1/2 w-56 hidden group-hover:block bg-black/95 border border-white/10 rounded-lg p-3 z-50 shadow-2xl"
                            >
                                <p class="text-white text-sm font-bold mb-1">
                                    {{ gear.name }}
                                </p>

                                <p class="text-xs text-gray-400 mb-2">
                                    Lv {{ gear.requirement_level }}
                                </p>

                                <div class="text-xs text-gray-300 space-y-1">
                                    <p v-if="gear.basic_stats?.attack">
                                        ⚔ Attack: {{ gear.basic_stats.attack }}
                                    </p>

                                    <p v-if="gear.basic_stats?.defense">
                                        🛡 Defense:
                                        {{ gear.basic_stats.defense }}
                                    </p>

                                    <p v-if="gear.basic_stats?.hp">
                                        ❤️ HP: {{ gear.basic_stats.hp }}
                                    </p>

                                    <p v-if="gear.basic_stats?.crit_rate">
                                        💥 Crit Rate:
                                        {{ gear.basic_stats.crit_rate }}%
                                    </p>
                                    <p v-if="gear.basic_stats?.speed">
                                        ⚡ Speed: {{ gear.basic_stats.speed }}
                                    </p>
                                    <p v-if="gear.basic_stats?.evasion">
                                        ⚔ Evasion:
                                        {{ gear.basic_stats.evasion }}
                                    </p>
                                </div>

                                <button
                                    class="mt-3 w-full text-xs py-1.5 rounded bg-green-500/10 border border-green-400/30 text-green-300 hover:bg-green-500/20 transition"
                                    @click="craftGear(gear)"
                                >
                                    Craft
                                </button>
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
