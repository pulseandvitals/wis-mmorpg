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
            <div class="flex flex-col items-center justify-center mt-10">
                <p
                    class="text-gray-300 mb-5 text-center text-sm leading-relaxed"
                >
                    A trial of fate awaits you. Place your gold and face a 50/50
                    chance — either your fortune is doubled, or it is lost to
                    the void.
                </p>

                <!-- INPUT -->
                <input
                    v-model="amount"
                    type="number"
                    placeholder="Enter amount"
                    class="w-full max-w-sm px-4 py-3 rounded-md bg-gray-800 border border-gray-700 text-white outline-none focus:border-indigo-400 transition"
                />

                <!-- BUTTON -->
                <button
                    @click="placeBet"
                    :disabled="loading"
                    class="mt-5 w-full max-w-sm py-3 rounded-md font-semibold text-white transition bg-green-600 hover:bg-green-500 shadow-md hover:shadow-green-500/30 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ loading ? "Rolling..." : "⚔ Bet Now" }}
                </button>

                <!-- RESULT -->
                <div v-if="result" class="mt-6 text-center">
                    <div
                        v-if="result === 'win'"
                        class="text-green-400 font-semibold text-lg"
                    >
                        🎉 You Won {{ reward }} Gold
                    </div>

                    <div v-else class="text-red-400 font-semibold text-lg">
                        💀 You Lost {{ amount }} Gold
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
