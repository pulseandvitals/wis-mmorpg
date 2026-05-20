<script setup>
import { pushAlert } from "@/Stores/GlobalAlert";
import { useForm, usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
const props = defineProps({
    player: Object,
});

const selectedGear = ref("");
const dialogues = [
    "But power has a price. Nothing here is free.",
    "Bring me rare materials… and I shall upgrade your gear.",
];

async function upgradeGear() {
    try {
        let res = await axios.post("/upgrade-gear", {
            gear: selectedGear.value,
        });
        pushAlert(res.data.message, "success");
        res = res.data;
    } catch (e) {
        pushAlert(e.response?.data?.message, "error");
    }
}
</script>
<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl text-white font-bold tracking-wide">
                    BlackSmith
                </h1>

                <button
                    @click="$emit('close')"
                    class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-white transition"
                >
                    Close
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div
                    class="col-span-2 rounded-2xl border border-gray-500 p-6 shadow-2xl shadow-black/40"
                >
                    <!-- HEADER -->
                    <div
                        class="flex items-center justify-between border-b border-white/10 pb-5 mb-6"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                class="w-16 h-16 rounded-2xl bg-orange-500/10 border border-orange-400/30 flex items-center justify-center text-4xl shadow-lg shadow-orange-500/10"
                            >
                                🔨
                            </div>

                            <div>
                                <p
                                    class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-300 to-yellow-400"
                                >
                                    Blacksmith
                                </p>

                                <p class="text-sm text-gray-400 mt-1">
                                    Master of forged weapons and armor
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- SELECT EQUIPPED GEAR -->
                    <div class="mb-6">
                        <label class="text-xs text-gray-400 mb-2 block">
                            Select Equipped Gear
                        </label>

                        <select
                            v-model="selectedGear"
                            class="w-full bg-black/60 border border-white/10 text-white rounded-xl px-4 py-3 outline-none focus:border-orange-400/60 focus:ring-2 focus:ring-orange-500/20 hover:border-orange-400/30 transition"
                        >
                            <option disabled value="">
                                -- Choose gear slot --
                            </option>

                            <option value="weapon">Weapon</option>
                            <option value="helm">Helm</option>
                            <option value="gloves">Gloves</option>
                            <option value="pants">Pants</option>
                            <option value="armor">Armor</option>
                            <option value="boots">Boots</option>
                            <option value="accessory">Accessory</option>
                            <option value="shield">Shield</option>
                        </select>
                    </div>

                    <!-- DIALOGUE LIST -->
                    <div class="space-y-3">
                        <div
                            v-for="(line, index) in dialogues"
                            :key="index"
                            class="flex items-center justify-between rounded-xl border border-white/10 bg-black/20 hover:bg-orange-500/5 transition px-5 py-4"
                        >
                            <!-- LEFT -->
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-lg font-bold text-orange-300"
                                >
                                    💬
                                </div>

                                <div>
                                    <p class="text-white font-medium">
                                        {{ line }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div
                        class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between"
                    >
                        <p class="text-xs text-gray-500">
                            Upgrading comes with risk — there’s a chance your
                            weapon will reset back to +0 if the upgrade fails.
                        </p>

                        <button
                            class="px-4 py-2 rounded-lg bg-yellow-500/20 border border-yellow-400/30 text-yellow-300 hover:bg-yellow-500/30"
                            @click="upgradeGear"
                        >
                            🔧 Start Forging
                        </button>
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
