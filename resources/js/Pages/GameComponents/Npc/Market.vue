<script setup>
import { computed, onMounted, ref } from "vue";
import { pushAlert } from "@/Stores/GlobalAlert";

/**
 * MARKET STATE
 */
const marketTabs = ["Wings", "Pets", "Auction"];
const selectedTab = ref("Wings");
const purchase_details = ref(0);
const loading = ref(false);

/**
 * DATA
 */
const wings = ref([]);
const pets = ref([]);
const auctions = ref([]);
const selectedItem = ref();
/**
 * FETCH DATA
 */
async function getWings() {
    const res = await axios.get("/get-wings");
    wings.value = res.data.wings || [];
    purchase_details.value = res.data.purchase_details || 0;
}

async function getPets() {
    const res = await axios.get("/get-pets");
    pets.value = res.data.pets || [];
}

async function getAuctions() {
    const res = await axios.get("/get-auctions");
    auctions.value = res.data.auctions || [];
}

/**
 * COMPUTED MARKET LIST
 */
const marketList = computed(() => {
    if (selectedTab.value === "Wings") return wings.value;
    if (selectedTab.value === "Pets") return pets.value;
    if (selectedTab.value === "Auction") return auctions.value;
    return [];
});

/**
 * ACTIONS
 */
async function buyItem(item) {
    try {
        loading.value = true;

        pushAlert(`Processing ${selectedTab.value} purchase...`, "info");

        const res = await axios.post("/market-buy", {
            gear: item,
            purchase_details: purchase_details.value,
        });

        pushAlert(res.data.message || "Purchase successful!", "success");
    } catch (err) {
        pushAlert(err.response?.data?.message || "Transaction failed", "error");
    } finally {
        loading.value = false;
    }
}

/**
 * INIT
 */
onMounted(async () => {
    await getWings();
    await getPets();
    await getAuctions();
});
</script>

<template>
    <div class="market-modal">
        <div class="market-content p-6">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl text-white font-bold tracking-wide">
                    Market House
                </h1>

                <button
                    @click="$emit('close')"
                    class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-white"
                >
                    Close
                </button>
            </div>

            <!-- TABS -->
            <div class="flex gap-2 mb-6 flex-wrap">
                <button
                    v-for="tab in marketTabs"
                    :key="tab"
                    @click="selectedTab = tab"
                    class="px-4 py-2 rounded-lg text-sm border transition"
                    :class="
                        selectedTab === tab
                            ? 'bg-purple-500/20 border-purple-400 text-white'
                            : 'bg-black/20 border-white/10 text-gray-300 hover:bg-white/5'
                    "
                >
                    {{ tab }}
                </button>
            </div>

            <!-- LIST -->
            <div class="space-y-3">
                <div
                    v-for="item in wings"
                    :key="item.id"
                    @click="selectedItem = item"
                    class="flex items-center justify-between px-5 py-4 rounded-xl border border-white/10 bg-black/20 hover:bg-purple-500/5 transition"
                >
                    <!-- LEFT -->
                    <div class="flex items-center gap-4 min-w-0">
                        <!-- ICON -->
                        <img
                            :src="`/gears/${item.name}.png`"
                            class="w-10 h-10 object-contain rounded-md"
                        />

                        <div class="min-w-0">
                            <p class="text-white font-bold truncate">
                                {{ item.name }}
                            </p>

                            <p class="text-xs text-gray-400 capitalize">
                                {{ item.type }}
                            </p>
                        </div>
                    </div>

                    <!-- ACTION -->
                    <button
                        @click="buyItem(item)"
                        :disabled="loading"
                        class="px-4 py-2 text-xs font-bold rounded-lg border border-blue-400/30 bg-blue-500/10 text-blue-300 hover:bg-blue-500/20 transition"
                    >
                        {{
                            loading
                                ? "Processing..."
                                : `💎 ${purchase_details.price}`
                        }}
                    </button>
                </div>
            </div>
            <!-- POPUP -->
            <div
                v-if="selectedItem"
                class="fixed inset-0 bg-black/60 flex items-center justify-center z-50"
            >
                <div
                    class="w-[320px] bg-[#111827] border border-white/10 rounded-xl p-5 shadow-2xl"
                >
                    <!-- HEADER -->
                    <div
                        class="flex items-center gap-3 mb-4 border-b border-white/10 pb-3"
                    >
                        <img
                            :src="`/gears/${selectedItem.name}.png`"
                            class="w-12 h-12 object-contain"
                        />

                        <div>
                            <p class="text-white font-bold">
                                {{ selectedItem.name }}
                            </p>
                            <p class="text-xs text-gray-400 capitalize">
                                Lvl.{{ selectedItem.requirement_level }}
                            </p>
                            <p class="text-xs text-gray-400 capitalize">
                                {{ selectedItem.type }}
                            </p>
                        </div>
                    </div>

                    <!-- STATS -->
                    <div class="text-xs text-gray-300 space-y-1">
                        <p v-if="selectedItem.basic_stats?.attack">
                            ⚔ Attack: {{ selectedItem.basic_stats.attack }}
                        </p>

                        <p v-if="selectedItem.basic_stats?.defense">
                            🛡 Defense: {{ selectedItem.basic_stats.defense }}
                        </p>

                        <p v-if="selectedItem.basic_stats?.speed">
                            ⚡ Speed: {{ selectedItem.basic_stats.speed }}
                        </p>

                        <p v-if="selectedItem.basic_stats?.hp">
                            ❤️ HP: {{ selectedItem.basic_stats.hp }}
                        </p>

                        <p v-if="selectedItem.basic_stats?.mp">
                            💧 MP: {{ selectedItem.basic_stats.mp }}
                        </p>

                        <p v-if="selectedItem.basic_stats?.crit">
                            💥 Crit: {{ selectedItem.basic_stats.crit }}%
                        </p>

                        <p v-if="selectedItem.basic_stats?.evasion">
                            🎯 Evasion: {{ selectedItem.basic_stats.evasion }}%
                        </p>
                    </div>

                    <!-- CLOSE -->
                    <button
                        @click="selectedItem = null"
                        class="mt-4 w-full py-2 rounded-lg bg-red-500/20 text-red-300 border border-red-400/30"
                    >
                        Close
                    </button>
                </div>
            </div>

            <!-- FOOTER -->
            <div
                class="mt-6 pt-4 border-t border-white/10 flex justify-between"
            >
                <p class="text-xs text-gray-500">
                    Wings • Pets • Auction System
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.market-content {
    width: 100%;
    max-width: 900px;
    min-height: 450px;

    background: #111827;
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.market-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);

    display: flex;
    align-items: center;
    justify-content: center;

    z-index: 999999;
}
</style>
