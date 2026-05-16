<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
const props = defineProps({
    player: Object,
});
const rankings = ref([]);
async function getRankings() {
    let resp = await axios.get("/get-ranking");
    rankings.value = resp.data.rankings;
}
onMounted(() => {
    getRankings();
});
</script>
<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl text-white font-bold tracking-wide">
                    Ranking
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
                                class="w-16 h-16 rounded-2xl bg-yellow-500/10 border border-yellow-400/30 flex items-center justify-center text-4xl shadow-lg shadow-yellow-500/10"
                            >
                                🏆
                            </div>

                            <div>
                                <p
                                    class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-400"
                                >
                                    Adventurer
                                </p>

                                <p class="text-sm text-gray-400 mt-1">
                                    The strongest warriors in the realm
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- RANK LIST -->
                    <div class="space-y-3">
                        <div
                            v-for="(player, index) in rankings"
                            :key="index"
                            class="flex items-center justify-between rounded-xl border border-white/10 bg-black/20 hover:bg-yellow-500/5 transition px-5 py-4"
                        >
                            <!-- LEFT -->
                            <div class="flex items-center gap-4">
                                <!-- RANK -->
                                <div
                                    class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-lg font-bold text-yellow-300"
                                >
                                    #{{ index + 1 }}
                                </div>

                                <!-- PLAYER -->
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-white font-bold">
                                            {{ player.name }}
                                        </p>

                                        <!-- CLASS -->
                                        <span
                                            class="text-xs px-2 py-1 rounded-lg bg-purple-500/20 text-purple-300 border border-purple-500/20"
                                        >
                                            {{ player.class_type }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT -->
                            <div class="text-right">
                                <p class="text-gray-300 font-extrabold text-lg">
                                    Lv. {{ player.current_level }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div
                        class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between"
                    >
                        <p class="text-xs text-gray-500">
                            Rankings refresh every 60 seconds
                        </p>
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
