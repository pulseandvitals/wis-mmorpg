<script setup>
import { computed, onMounted, ref } from "vue";
import { pushAlert } from "@/Stores/GlobalAlert";

const potions = ref([]);
const selectedPotion = ref(null);
const loading = ref(false);
const currentPage = ref(1);
const perPage = 4;

const paginatedPotions = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    const end = start + perPage;
    return potions.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(potions.value.length / perPage);
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

/**
 * BUY POTION
 */
async function buyPotion(potion) {
    try {
        loading.value = true;
        pushAlert(`Buying ${potion.name}...`, "info");

        const res = await axios.post("/buy-potion", {
            potion: potion,
        });

        pushAlert(
            res.data.message || "Potion purchased successfully!",
            "success",
        );
        loading.value = false;
        return res.data;
    } catch (error) {
        loading.value = false;
        pushAlert(
            error.response?.data?.message || "Failed to buy potion.",
            "error",
        );
        console.error("Buy Potion Error:", error);
    }
}

async function getPotions() {
    const resp = await axios.get("/get-potions");
    potions.value = resp.data.potions || [];
}
/**
 * INIT
 */
onMounted(async () => {
    getPotions();
});
</script>

<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl text-white font-bold tracking-wide">
                    🧪 Potion Shop
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
                                class="w-16 h-16 rounded-2xl bg-purple-500/10 border border-purple-400/30 flex items-center justify-center text-4xl shadow-lg shadow-purple-500/10"
                            >
                                🧪
                            </div>

                            <div>
                                <p class="text-sm text-gray-400 mt-1">
                                    Buy potions for your adventure
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- POTION LIST -->
                    <div class="space-y-3">
                        <div
                            v-for="(potion, index) in paginatedPotions"
                            :key="potion.id"
                            tabindex="0"
                            class="flex items-center justify-between rounded-xl border border-white/10 bg-black/20 hover:bg-purple-500/5 transition px-5 py-4 cursor-pointer outline-none focus:ring-2 focus:ring-purple-400/40"
                            @click="selectedPotion = potion"
                        >
                            <!-- LEFT -->
                            <div
                                class="flex items-center justify-start w-full gap-4"
                            >
                                <!-- LEFT SIDE -->
                                <div class="flex items-center gap-4 min-w-0">
                                    <img
                                        :src="`/potions/${potion.name}.png`"
                                        class="w-10 h-10 object-contain flex-shrink-0"
                                    />

                                    <p class="text-white font-bold truncate">
                                        {{ potion.name }}
                                    </p>
                                </div>
                            </div>

                            <!-- RIGHT -->
                            <div class="text-right">
                                <button
                                    class="px-4 py-2 text-xs font-bold rounded-lg border border-green-400/30 bg-green-500/10 text-green-300 hover:bg-green-500/20 hover:border-green-400/60 transition"
                                    :disabled="loading"
                                    @click="buyPotion(potion)"
                                >
                                    {{ loading ? "Buying..." : "Buy" }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- PAGINATION -->
                    <div
                        class="flex justify-center items-center gap-4 mt-6 pt-4 border-t border-white/10"
                    >
                        <button
                            @click="prevPage"
                            :disabled="currentPage === 1"
                            class="px-4 py-2 rounded-lg text-sm border transition"
                            :class="
                                currentPage === 1
                                    ? 'bg-black/20 border-white/10 text-gray-500 cursor-not-allowed'
                                    : 'bg-purple-500/20 border-purple-400 text-white hover:bg-purple-500/30'
                            "
                        >
                            Previous
                        </button>
                        <span class="text-white text-sm">
                            Page {{ currentPage }} of {{ totalPages }}
                        </span>
                        <button
                            @click="nextPage"
                            :disabled="currentPage === totalPages"
                            class="px-4 py-2 rounded-lg text-sm border transition"
                            :class="
                                currentPage === totalPages
                                    ? 'bg-black/20 border-white/10 text-gray-500 cursor-not-allowed'
                                    : 'bg-purple-500/20 border-purple-400 text-white hover:bg-purple-500/30'
                            "
                        >
                            Next
                        </button>
                    </div>

                    <transition name="fade">
                        <div
                            v-if="selectedPotion"
                            class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none"
                        >
                            <div
                                class="w-[340px] rounded-2xl border border-white/10 bg-black/85 p-5 shadow-2xl"
                            >
                                <!-- HEADER -->
                                <div
                                    class="flex items-center gap-3 border-b border-white/10 pb-4 mb-4"
                                >
                                    <div>
                                        <p class="text-white font-bold">
                                            {{ selectedPotion.name }}
                                        </p>
                                        <p
                                            class="text-xs text-gray-400 capitalize"
                                        >
                                            {{ selectedPotion.type }} Potion
                                        </p>
                                    </div>
                                </div>

                                <!-- DESCRIPTION -->
                                <div class="mb-4">
                                    <p class="text-sm text-gray-300">
                                        {{ selectedPotion.description }}
                                    </p>
                                </div>

                                <!-- PRICE -->
                                <div class="mt-4 pt-3 border-t border-white/10">
                                    <p class="text-xs text-yellow-400">
                                        {{
                                            selectedPotion.sell_type === "gold"
                                                ? "💰"
                                                : "💎"
                                        }}
                                        {{ selectedPotion.item_price }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </transition>

                    <!-- FOOTER -->
                    <div
                        class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between"
                    >
                        <p class="text-xs text-gray-500">
                            Stock up on potions before your journey!
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

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
