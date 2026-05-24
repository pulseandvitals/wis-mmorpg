<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl text-white font-bold">Cards</h1>

                <button
                    @click="$emit('close')"
                    class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-white"
                >
                    Close
                </button>
            </div>

            <!-- CARD GRID -->
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-3">
                    <div
                        class="bg-gray-900 border border-gray-700 rounded-xl p-3"
                    >
                        <!-- HEADER -->
                        <div class="text-sm text-gray-300 font-semibold mb-3">
                            Card Collection
                        </div>

                        <!-- GRID -->
                        <div class="grid grid-cols-5 gap-2">
                            <div
                                v-for="card in paginatedCards"
                                :key="card.id"
                                @click="openCard(card)"
                                class="relative cursor-pointer group"
                            >
                                <!-- SLOT -->
                                <div
                                    class="aspect-square bg-gradient-to-b from-gray-800 to-gray-900 border border-gray-700 rounded-lg flex flex-col items-center justify-center transition hover:border-purple-400 hover:scale-105 hover:shadow-lg hover:shadow-purple-500/20 p-2"
                                >
                                    <!-- ICON -->
                                    <img
                                        :src="`/cards/${card.name}.png`"
                                        class="w-10 h-10 object-contain drop-shadow-lg"
                                    />

                                    <!-- NAME -->
                                    <div
                                        class="text-[10px] text-gray-300 text-center mt-1"
                                    >
                                        {{ card.name }}
                                    </div>
                                </div>

                                <!-- GLOW -->
                                <div
                                    class="absolute inset-0 rounded-lg border border-purple-400 opacity-0 group-hover:opacity-100 transition pointer-events-none"
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
                    </div>
                </div>
            </div>

            <!-- CARD DETAIL MODAL -->
            <div
                v-if="selectedCard"
                class="fixed inset-0 bg-black/70 flex items-center justify-center z-50"
            >
                <div
                    class="w-[350px] bg-gradient-to-b from-gray-900 to-black border border-gray-500 rounded-xl p-4 shadow-2xl"
                >
                    <!-- HEADER -->
                    <div class="flex items-center gap-3">
                        <div
                            class="w-16 h-16 bg-gray-800 border border-gray-700 rounded-lg flex items-center justify-center"
                        >
                            <img
                                :src="`/cards/${selectedCard.name}.png`"
                                class="w-12 h-12 object-contain"
                            />
                        </div>

                        <div>
                            <h2 class="text-purple-400 font-bold text-lg">
                                {{ selectedCard.name }}
                            </h2>

                            <p class="text-gray-400 text-xs">
                                Card Slot {{ selectedCard.card_slot }}
                            </p>
                        </div>
                    </div>

                    <!-- EFFECTS -->
                    <div class="mt-4 space-y-2 text-sm">
                        <div
                            v-for="(eff, i) in parsedEffects"
                            :key="i"
                            class="flex justify-between border-b border-gray-800 pb-1"
                        >
                            <span class="text-gray-400 capitalize">
                                {{ eff.stat }}
                            </span>

                            <span class="text-green-400 font-semibold">
                                +{{ eff.value }}
                                <span v-if="eff.value_type === 'percent'"
                                    >%</span
                                >
                            </span>
                        </div>
                    </div>

                    <!-- DESCRIPTION -->
                    <div
                        class="mt-4 bg-gray-900 border border-gray-800 rounded-lg p-3 text-xs text-gray-300"
                    >
                        {{ selectedCard.description }}
                    </div>

                    <!-- ACTION -->
                    <div class="mt-4 flex gap-2">
                        <button
                            @click="closeCard"
                            class="flex-1 bg-gray-800 hover:bg-gray-700 text-white py-2 rounded-lg transition"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";

const emit = defineEmits(["close"]);

const cards = ref([]);
const selectedCard = ref(null);

const currentPage = ref(1);
const perPage = 15;

async function loadCards() {
    const res = await axios.get("/get-cards");
    cards.value = res.data.cards;
}

const paginatedCards = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    return cards.value.slice(start, start + perPage);
});

const totalPages = computed(() => {
    return Math.ceil(cards.value.length / perPage);
});

function nextPage() {
    if (currentPage.value < totalPages.value) currentPage.value++;
}

function prevPage() {
    if (currentPage.value > 1) currentPage.value--;
}

function openCard(card) {
    selectedCard.value = card;
}

function closeCard() {
    selectedCard.value = null;
}

const parsedEffects = computed(() => {
    try {
        return selectedCard.value?.effects
            ? JSON.parse(selectedCard.value.effects)
            : [];
    } catch {
        return [];
    }
});

onMounted(() => {
    loadCards();
});
</script>

<style scoped>
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
