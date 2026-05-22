<script setup>
import { ref } from "vue";

const emit = defineEmits(["close"]);

const loading = ref(false);
const answer = ref("");
const result = ref(null);

// server-provided rumbled monster name
const question = ref("Gonodrake Rethyul"); // example scrambled name

// correct answer (should come from backend in real setup)
const correctAnswer = ref("Dragon Knight");

// simulate submit
const placeBet = () => {
    if (!answer.value) return;

    loading.value = true;

    setTimeout(() => {
        const isCorrect =
            answer.value.trim().toLowerCase() ===
            correctAnswer.value.toLowerCase();

        result.value = isCorrect ? "win" : "lose";
        loading.value = false;
    }, 1000);
};
</script>

<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl text-white font-bold tracking-wide">
                    Trivia Event
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
                        Monster Trivia
                    </h2>

                    <p class="text-gray-400 text-sm leading-relaxed">
                        A rumbled monster name has been summoned. Guess the
                        correct monster name to win rewards.
                    </p>
                </div>

                <!-- QUESTION DISPLAY -->
                <div
                    class="w-full max-w-sm bg-gray-900/60 border border-gray-700 rounded-xl p-4 backdrop-blur mb-4"
                >
                    <label class="text-xs text-gray-400 mb-2 block">
                        Rumbled Monster Name
                    </label>

                    <div
                        class="w-full px-4 py-3 rounded-lg bg-gray-800 border border-gray-700 text-white text-center tracking-widest"
                    >
                        {{ question }}
                    </div>

                    <div
                        class="flex justify-between text-[10px] text-gray-500 mt-2"
                    >
                        <span>Hint: Scrambled</span>
                        <span>Type: Trivia</span>
                    </div>
                </div>

                <!-- INPUT CARD -->
                <div
                    class="w-full max-w-sm bg-gray-900/60 border border-gray-700 rounded-xl p-4 backdrop-blur"
                >
                    <label class="text-xs text-gray-400 mb-2 block">
                        Your Answer
                    </label>

                    <input
                        v-model="answer"
                        type="text"
                        placeholder="Guess the monster name"
                        class="w-full px-4 py-3 rounded-lg bg-gray-800 border border-gray-700 text-white outline-none focus:border-indigo-400 transition"
                    />
                </div>

                <!-- BUTTON -->
                <button
                    @click="placeBet"
                    :disabled="loading || !answer"
                    class="mt-5 px-6 py-3 text-sm font-bold rounded-lg border border-green-400/30 bg-green-500/10 text-green-300 hover:bg-green-500/20 disabled:opacity-40 disabled:cursor-not-allowed transition flex items-center gap-2"
                >
                    <span v-if="loading" class="animate-pulse">
                        Checking...
                    </span>
                    <span v-else> 🎯 Submit Answer </span>
                </button>

                <!-- RESULT -->
                <transition name="fade">
                    <div v-if="result" class="mt-6 text-center">
                        <div
                            v-if="result === 'win'"
                            class="text-green-400 font-bold text-xl flex flex-col items-center gap-1"
                        >
                            🎉 Correct Answer
                            <span class="text-sm text-gray-300">
                                You identified the monster!
                            </span>
                        </div>

                        <div
                            v-else
                            class="text-red-400 font-bold text-xl flex flex-col items-center gap-1"
                        >
                            💀 Wrong Answer
                            <span class="text-sm text-gray-400">
                                Try again next time
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
