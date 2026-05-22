<script setup>
import { ref } from "vue";
import axios from "axios";
import { pushAlert } from "@/Stores/GlobalAlert";

const amount = ref("");
const loading = ref(false);

const result = ref(null); // win / lose
const reward = ref(0);

async function placeBet() {
    if (!amount.value || amount.value <= 0) return;

    loading.value = true;
    result.value = null;

    try {
        const response = await axios.post("/mini-event/bet", {
            amount: amount.value,
        });

        result.value = response.data.result;
        reward.value = response.data.reward;
        pushAlert(response.data.message, "success");
    } catch (e) {
        console.error(e);
        pushAlert(e.data.message, "error");
    }

    loading.value = false;
}
</script>

<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl text-white font-bold tracking-wide">
                    Bet Event
                </h1>

                <button
                    @click="$emit('close')"
                    class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-white transition"
                >
                    Close
                </button>
            </div>

            <!-- BODY -->
            <div class="flex flex-col items-center justify-center mt-10 px-4">
                <!-- TITLE / DESCRIPTION -->
                <div class="text-center max-w-md mb-6">
                    <h2 class="text-lg font-bold text-white mb-2">
                        Trial of Fate
                    </h2>

                    <p class="text-gray-400 text-sm leading-relaxed">
                        A trial of fate awaits you. Place your gold and face a
                        <span class="text-white font-semibold"
                            >50/50 chance</span
                        >
                        — either your fortune is doubled, or it is lost to the
                        void.
                    </p>
                </div>

                <!-- INPUT CARD -->
                <div
                    class="w-full max-w-sm bg-gray-900/60 border border-gray-700 rounded-xl p-4 backdrop-blur"
                >
                    <label class="text-xs text-gray-400 mb-2 block">
                        Bet Amount
                    </label>

                    <input
                        v-model="amount"
                        type="number"
                        placeholder="Enter amount"
                        class="w-full px-4 py-3 rounded-lg bg-gray-800 border border-gray-700 text-white outline-none focus:border-indigo-400 transition"
                    />

                    <!-- QUICK INFO -->
                    <div
                        class="flex justify-between text-[10px] text-gray-500 mt-2"
                    >
                        <span>Min: 1</span>
                        <span>Risk: High</span>
                    </div>
                </div>

                <!-- BUTTON -->
                <button
                    @click="placeBet"
                    :disabled="loading || !amount"
                    class="mt-5 px-6 py-3 text-sm font-bold rounded-lg border border-green-400/30 bg-green-500/10 text-green-300 hover:bg-green-500/20 disabled:opacity-40 disabled:cursor-not-allowed transition flex items-center gap-2"
                >
                    <span v-if="loading" class="animate-pulse">Rolling...</span>
                    <span v-else>⚔ Enter the Trial</span>
                </button>

                <!-- RESULT -->
                <transition name="fade">
                    <div v-if="result" class="mt-6 text-center">
                        <div
                            v-if="result === 'win'"
                            class="text-green-400 font-bold text-xl flex flex-col items-center gap-1"
                        >
                            🎉 Victory
                            <span class="text-sm text-gray-300">
                                +{{ reward }} Gold earned
                            </span>
                        </div>

                        <div
                            v-else
                            class="text-red-400 font-bold text-xl flex flex-col items-center gap-1"
                        >
                            💀 Defeat
                            <span class="text-sm text-gray-400">
                                -{{ amount }} Gold lost
                            </span>
                        </div>
                    </div>
                </transition>
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
