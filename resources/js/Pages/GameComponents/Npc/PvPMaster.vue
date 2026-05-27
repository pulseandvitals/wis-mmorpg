<script setup>
import axios from "axios";
import { pushAlert } from "@/Stores/GlobalAlert";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    player: Object,
    all_maps: Object,
});
const form = useForm({});
const emit = defineEmits(["close"]);
const pvpMap = props.all_maps.find((map) => map.name === "Floating Island");
async function enterArena() {
    form.get(route("world.map", pvpMap.map_id), {
        onStart: () => emit("close"),
        onFinish: () => pushAlert(pvpMap?.name),
        onError: (errors) => {
            pushAlert(errors.message || "Level not met.", "error");
        },
    });
}
</script>

<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl text-white font-bold tracking-wide">
                    PvP Master
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
                    class="col-span-2 rounded-2xl border border-purple-500/30 p-6"
                >
                    <!-- HEADER ICON -->
                    <div class="flex items-center gap-4 mb-5">
                        <div
                            class="w-16 h-16 rounded-full bg-purple-500/10 border border-purple-400/20 flex items-center justify-center text-3xl"
                        >
                            ⚔️
                        </div>

                        <div>
                            <p class="text-xs text-purple-300">
                                Trial of Strength & Glory
                            </p>
                        </div>
                    </div>

                    <!-- DIALOGUE -->
                    <div class="text-gray-200 text-sm leading-7 space-y-4">
                        <p>
                            So… another challenger steps forward. I wonder if
                            you are here for glory… or for survival.
                        </p>

                        <p>
                            The PvP Arena is not a place for hesitation. Once
                            you enter, there is no protection, no mercy, and no
                            retreat. Only warriors with true resolve make it out
                            with honor.
                        </p>

                        <p>
                            You will face opponents forged by battle, each one
                            hungry for victory. Some rely on brute strength…
                            others on timing, strategy, or pure instinct.
                        </p>

                        <p>
                            If you are defeated, you will not lose everything…
                            but your pride will not be spared. If you win, your
                            name will be remembered in the Arena Hall of
                            Legends.
                        </p>

                        <p class="italic text-purple-200">
                            “Step forward only if your heart does not tremble.”
                        </p>
                    </div>

                    <!-- ACTIONS -->
                    <div class="mt-8 flex items-center gap-4">
                        <button
                            class="px-8 py-3 rounded-xl bg-green-500/60 hover:bg-green-500/10 transition text-white font-bold border border-white/10"
                            @click="enterArena"
                        >
                            ⚔️ ENTER ARENA
                        </button>

                        <span class="text-xs text-gray-400">
                            You will be matched with a real opponent
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.npc-modal-content {
    width: 100%;
    max-width: 900px;
    min-height: 420px;

    background: #111827;
    border-radius: 24px;

    border: 1px solid rgba(255, 255, 255, 0.08);

    position: relative;
    z-index: 999999999;
    pointer-events: auto;
}

.npc-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.55);

    display: flex;
    align-items: center;
    justify-content: center;

    z-index: 999999999;
}
</style>
