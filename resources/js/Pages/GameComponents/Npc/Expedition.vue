<script setup>
import { computed, onMounted, onUnmounted, ref } from "vue";
import { pushAlert } from "@/Stores/GlobalAlert";
const isExpeditionOpen = ref(false);
const activeTab = ref("maps");
const loading = ref(false);

const expedition = ref(null);

const expeditionForm = ref({
    map_name: "",
    hours: 1,
});

const expeditionMaps = ref([
    {
        id: 1,
        name: "Valdora Grassland",
        level_required: 1,
        monsters: 30,
        image: "/maps/Valdora Grassland.png",
        loot: [
            "Rabbit fur",
            "Orc Axe",
            "Ice Crystal",
            "War Badge",
            "Small Meat",
        ],
    },
    {
        id: 2,
        name: "Dark Forest",
        level_required: 10,
        monsters: 30,
        image: "/maps/Dark Forest.png",
        loot: [
            "Hell Core",
            "Goblin Ear",
            "Lightning Core",
            "Rot Flesh",
            "Burning Gem",
            "Rusty Shield",
            "Zombie Bone",
        ],
    },
    {
        id: 3,
        name: "Crystal Cave",
        level_required: 20,
        monsters: 30,
        image: "/maps/Crystal Cave.png",
        loot: [
            "Dark Cloth",
            "Horn Fragment",
            "Lizard Scale",
            "Boar Tusks",
            "Storm Essence",
            "Shadow Dagger",
            "Fire Sac",
            "Wild Meat",
        ],
    },
    {
        id: 4,
        name: "Volcanic Wasteland",
        level_required: 30,
        monsters: 30,
        image: "/maps/Volcanic Wasteland.png",
        loot: [
            "Mutant Wool",
            "Vampire Fang",
            "Burnt Meat",
            "Ancient Scale",
            "Wolf Pelt",
            "Strange Meat",
            "Blood Crystal",
            "Ember Core",
            "Dragon Heart",
            "Sharp Fang",
        ],
    },
    {
        id: 5,
        name: "Sky Islands",
        level_required: 40,
        monsters: 30,
        image: "/maps/Sky Islands.png",
        loot: ["Ancient Scale", "Dragon Heart", "Celebeam Gem", "Seleri Gem"],
    },
]);

async function getMyExpedition() {
    try {
        const res = await axios.get("/my-expedition");

        if (res.data.expedition) {
            const exp = res.data.expedition;

            exp.remaining = exp.ends_at;

            expedition.value = exp;
        }

        expedition.value = res.data.expedition;
    } catch (e) {}
}

async function startExpedition() {
    try {
        loading.value = true;

        let res = await axios.post("/start-expedition", {
            map_name: expeditionForm.value.map_name,
            hours: expeditionForm.value.hours,
        });

        getMyExpedition();
        expeditionForm.value.map_name = null;
        pushAlert(res.data.message, "success");

        activeTab.value = "my";
    } catch (e) {
        pushAlert(e.response.data.message, "error");
    } finally {
        loading.value = false;
    }
}

async function claimExpedition() {
    try {
        loading.value = true;

        const res = await axios.post("/claim-expedition", {
            expedition: expedition.value,
        });

        expedition.value = null;

        pushAlert(res.data.message, "success");
    } catch (e) {
        pushAlert(e.response.data.message, "error");
    } finally {
        loading.value = false;
    }
}

onMounted(async () => {
    await getMyExpedition();
});

let interval;

onMounted(() => {
    interval = setInterval(() => {
        if (!expedition.value) return;

        if (expedition.value.remaining > 0) {
            expedition.value.remaining--;
        }
    }, 1000);
});

onUnmounted(() => clearInterval(interval));
const formatTime = (sec) => {
    if (sec <= 0) return "READY TO CLAIM";

    const m = Math.floor(sec / 60);
    const s = sec % 60;

    return `${m}:${s.toString().padStart(2, "0")}`;
};
</script>

<template>
    <!-- NPC -->
    <div class="npc-layer">
        <div
            class="npc"
            :style="{ top: '100px', left: '950px' }"
            @click="isExpeditionOpen = true"
        >
            <img src="/npc/Expedition.gif" class="npc-img" />

            <div class="npc-label">Expedition Master</div>
        </div>
    </div>

    <!-- MAIN MODAL -->
    <div class="ui-layer" v-if="isExpeditionOpen">
        <div class="modal">
            <!-- HEADER -->
            <div class="header">
                <h1>Expedition Hall</h1>

                <button @click="isExpeditionOpen = false">Close</button>
            </div>

            <!-- TABS -->
            <div class="tabs">
                <button
                    @click="activeTab = 'maps'"
                    :class="{
                        active: activeTab === 'maps',
                    }"
                >
                    Maps
                </button>

                <button
                    @click="activeTab = 'my'"
                    :class="{
                        active: activeTab === 'my',
                    }"
                >
                    My Expedition
                </button>
            </div>

            <!-- CONTENT -->
            <div class="content">
                <!-- MAP LIST -->
                <div v-if="activeTab === 'maps'" class="list">
                    <div
                        v-for="map in expeditionMaps"
                        :key="map.id"
                        class="relative overflow-hidden rounded-lg border border-gray-700 hover:border-yellow-500 transition"
                    >
                        <!-- Background Image -->
                        <img
                            :src="map.image"
                            class="absolute inset-0 w-full h-full object-cover"
                        />

                        <!-- Dark Overlay -->
                        <div class="absolute inset-0 bg-black/70"></div>

                        <!-- Content -->
                        <div
                            class="relative z-10 flex items-center justify-between px-3 py-2"
                        >
                            <div>
                                <div
                                    class="text-xl font-bold text-white drop-shadow"
                                >
                                    {{ map.name }}
                                </div>

                                <div
                                    class="text-sm text-gray-300"
                                    :class="{
                                        'text-red-500':
                                            map.level_required >
                                            $page.props.auth.user.player
                                                ?.current_level,
                                    }"
                                >
                                    Required Level:
                                    {{ map.level_required }}
                                </div>

                                <div
                                    class="text-sm text-green-400 font-semibold"
                                >
                                    {{ map.monsters.toLocaleString() }}
                                    Monsters / Hour
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <div
                                        v-for="(item, index) in map.loot"
                                        :key="index"
                                        class="flex items-center gap-1 bg-black/40 px-2 py-1 rounded"
                                    >
                                        <img
                                            :src="`/materials/${item}.png`"
                                            class="w-5 h-5 object-cover rounded"
                                        />
                                    </div>
                                </div>
                            </div>

                            <button
                                v-if="
                                    map.level_required <=
                                    $page.props.auth.user.player?.current_level
                                "
                                class="relative z-20 px-4 py-2 bg-green-600 hover:bg-green-500 rounded text-white font-semibold"
                                :disabled="
                                    map.level_required >
                                    $page.props.auth.user.player?.current_level
                                "
                                @click="expeditionForm.map_name = map.name"
                            >
                                Select
                            </button>
                        </div>
                    </div>
                </div>

                <!-- MY EXPEDITION -->
                <div v-if="activeTab === 'my'">
                    <div
                        v-if="expedition"
                        class="relative overflow-hidden rounded-lg border border-gray-700"
                    >
                        <!-- BACKGROUND IMAGE -->
                        <img
                            :src="
                                expeditionMaps.find(
                                    (m) => m.name === expedition.map_name,
                                )?.image
                            "
                            class="absolute inset-0 w-full h-full object-cover"
                        />

                        <!-- DARK OVERLAY -->
                        <div class="absolute inset-0 bg-black/70"></div>

                        <!-- CONTENT -->
                        <div class="relative z-10 p-4">
                            <div class="text-xl font-bold text-white">
                                {{ expedition.map_name }}
                            </div>

                            <div class="text-gray-300 mt-2">
                                Duration: {{ expedition.hours }} Hours
                            </div>

                            <div class="text-gray-300 mt-2">
                                Ends In:
                                {{ formatTime(expedition.remaining) }}
                            </div>

                            <div class="text-green-400 mt-2">
                                Expected EXP:
                                {{ expedition.exp_reward?.toLocaleString() }}
                            </div>
                            <div class="text-green-400">
                                Expected Gold:
                                {{ expedition.gold_reward?.toLocaleString() }}
                            </div>

                            <!-- WARNING MESSAGE -->
                            <div
                                class="text-xs text-yellow-400 mt-3 leading-relaxed"
                            >
                                ⚠ You may close the Expedition Hall safely. Do
                                not refresh the page or leave the map while
                                expedition is ongoing, or progress display may
                                reset.
                            </div>

                            <!-- STATUS -->
                            <div class="mt-4" v-if="expedition.remaining <= 0">
                                <button
                                    class="px-4 py-2 bg-green-600 hover:bg-green-500 rounded text-white"
                                    @click="claimExpedition"
                                >
                                    Claim Rewards
                                </button>
                            </div>

                            <div v-else class="text-yellow-400 mt-4">
                                Expedition in progress...
                            </div>
                        </div>
                    </div>

                    <div v-else class="empty">No active expedition.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- START EXPEDITION MODAL -->
    <div class="create-modal" v-if="expeditionForm.map_name">
        <div class="create-box">
            <h2>Start Expedition</h2>

            <label> How long do you want to explore? </label>

            <select
                v-model="expeditionForm.hours"
                class="w-full mt-2 p-2 rounded bg-gray-800 text-white"
            >
                <option :value="1">1 Hour</option>

                <option :value="2">2 Hours</option>

                <option :value="4">4 Hours</option>

                <option :value="6">6 Hours</option>

                <option :value="12">12 Hours</option>
            </select>

            <div class="actions">
                <button class="cancel" @click="expeditionForm.map_name = null">
                    Cancel
                </button>

                <button class="confirm" @click="startExpedition()">
                    Start
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* ================= GAME LAYER ================= */
.game-layer {
    width: 100%;
    height: 100%;
}

/* ================= NPC LAYER ================= */
.npc-layer {
    position: absolute;
    inset: 0;
    z-index: 10;

    pointer-events: none; /* ✅ CRITICAL FIX */
}

.npc {
    position: absolute;
    display: flex;
    flex-direction: column;
    align-items: center;

    pointer-events: auto; /* ONLY this receives clicks */
    cursor: pointer;
}

.npc-img {
    width: 112px;
    height: 112px;
    object-fit: contain;
    transition: 0.2s;
}

.npc:hover .npc-img {
    transform: scale(1.05);
}

.npc-label {
    margin-top: 5px;
    font-size: 12px;
    color: white;
    background: rgba(0, 0, 0, 0.6);
    padding: 4px 8px;
    border-radius: 6px;
}

/* ================= UI LAYER ================= */
.ui-layer {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0, 0, 0, 0.55);
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ================= MODAL (IMPROVED STYLE ONLY) ================= */
.modal {
    width: 900px;
    max-width: 95vw;
    min-height: 450px;

    background: #111827;
    border-radius: 20px;

    border: 1px solid rgba(255, 255, 255, 0.08);

    box-shadow:
        0 10px 40px rgba(0, 0, 0, 0.6),
        inset 0 1px 0 rgba(255, 255, 255, 0.05);

    padding: 20px;
}

/* HEADER */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.header h1 {
    font-size: 24px;
    font-weight: bold;
}

.header button {
    background: #dc2626;
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
}

/* TABS */
.tabs {
    display: flex;
    gap: 10px;
    margin: 15px 0;
}

.tabs button {
    padding: 6px 12px;
    border-radius: 8px;
    border: none;
    background: rgba(255, 255, 255, 0.06);
    color: #ccc;
    cursor: pointer;
    transition: 0.2s;
}

.tabs button:hover {
    background: rgba(255, 255, 255, 0.12);
}

.tabs button.active {
    background: #2563eb;
    color: white;
}

/* CONTENT */
.content {
    border: 1px solid rgba(255, 255, 255, 0.08);
    padding: 15px;
    border-radius: 12px;
    background: rgba(0, 0, 0, 0.2);
}

/* LIST */
.list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.row {
    display: flex;
    justify-content: space-between;
    padding: 12px;
    border-radius: 10px;
    background: rgba(0, 0, 0, 0.35);
    cursor: pointer;
    transition: 0.2s;
}

.row:hover {
    background: rgba(59, 130, 246, 0.15);
}

.left {
    display: flex;
    gap: 10px;
    align-items: center;
}

.rank {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.06);
    border-radius: 8px;
    color: #60a5fa;
    font-weight: bold;
}

.name {
    color: white;
    font-weight: bold;
}

.sub {
    font-size: 12px;
    color: gray;
}

.right {
    text-align: right;
    color: white;
}

/* MY GUILD */
.box {
    padding: 15px;
    border-radius: 10px;
    background: rgba(0, 0, 0, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.06);
}

.title {
    font-size: 18px;
    color: white;
    font-weight: bold;
}

.stats {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    color: #ccc;
    font-size: 13px;
}

/* EMPTY */
.empty {
    text-align: center;
    color: gray;
    padding: 20px;
}

/* LEAVE BUTTON */
.leave {
    width: 100%;
    margin-top: 10px;
    padding: 10px;
    background: #dc2626;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.leave:hover {
    background: #ef4444;
}

/* ================= CREATE GUILD BUTTON ================= */
.create-btn {
    padding: 8px 12px;
    background: #16a34a;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 12px;
}

.create-btn:hover {
    background: #22c55e;
}

/* ================= CREATE MODAL ================= */
.create-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
}

.create-box {
    width: 350px;
    background: #111827;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 15px;
    color: white;
}

.create-box h2 {
    margin-bottom: 10px;
    font-size: 18px;
    font-weight: bold;
}

.create-box label {
    font-size: 12px;
    color: #aaa;
    display: block;
    margin-top: 10px;
}

.create-box input,
.create-box textarea {
    width: 100%;
    margin-top: 5px;
    padding: 8px;
    border-radius: 6px;
    border: none;
    background: rgba(255, 255, 255, 0.05);
    color: white;
    outline: none;
}

.create-box textarea {
    resize: none;
    height: 70px;
}

.cost {
    margin-top: 10px;
    font-size: 12px;
    color: #fbbf24;
}

.actions {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
}

.cancel {
    padding: 6px 10px;
    background: gray;
    border: none;
    border-radius: 6px;
    color: white;
    cursor: pointer;
}

.confirm {
    padding: 6px 10px;
    background: #16a34a;
    border: none;
    border-radius: 6px;
    color: white;
    cursor: pointer;
}

.confirm:hover {
    background: #22c55e;
}
.payment-type-buttons {
    display: flex;
    gap: 10px;
    margin: 10px 0;
}

.choice-btn {
    padding: 2px 8px;
    border: 1px solid #444;
    background: #1f2937;
    color: #fff;
    cursor: pointer;
    border-radius: 6px;
    transition: 0.2s;
}

.choice-btn:hover {
    background: #374151;
}

.choice-btn.active {
    background: #facc15;
    color: #111;
    border-color: #facc15;
}
</style>
