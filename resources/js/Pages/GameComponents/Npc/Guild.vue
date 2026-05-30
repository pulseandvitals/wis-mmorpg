<script setup>
import { pushAlert } from "@/Stores/GlobalAlert";
import { onMounted, ref } from "vue";

const isGuildOpen = ref(false);
const activeTab = ref("list");
const showCreateGuild = ref(false);
const showOpenContribution = ref(false);
const createGuildCost = 500000;
const createGuildDiamondCost = 599;
const loading = ref(false);
const guild = ref(null);
const guilds = ref(null);

const createGuildForm = ref({
    name: "",
    contribution: "",
    payment_type: "",
});
const fileInput = ref(null);

const triggerUpload = () => {
    fileInput.value?.click();
};

const handleUpload = async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const isFree = !guild.value.icon;
    const GUILD_ICON_COST = 299;
    if (!isFree) {
        const confirmUpload = confirm(
            `Changing your guild icon will cost ${GUILD_ICON_COST} diamonds. Do you want to continue?`,
        );

        if (!confirmUpload) {
            event.target.value = null; // reset input
            return;
        }
    }

    // preview locally
    const reader = new FileReader();
    reader.onload = (e) => {
        guild.value.icon = e.target.result;
    };
    reader.readAsDataURL(file);
    try {
        loading.value = true;

        const formData = new FormData();
        formData.append("icon", file);

        const res = await axios.post("/apply-icon", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        guild.value = res.data.guild;

        pushAlert(res.data.message, "success");
    } catch (e) {
        pushAlert(e?.response?.data?.message || "Upload failed", "error");
    } finally {
        loading.value = false;
    }
};
function openCreateGuild() {
    showCreateGuild.value = true;
}

function closeCreateGuild() {
    showCreateGuild.value = false;
}

async function createGuild() {
    try {
        loading.value = true;
        let res = await axios.post("/create-guild", {
            name: createGuildForm.value.name,
            payment_type: createGuildForm.value.payment_type,
        });
        guild.value = res.data.guild;
        pushAlert(res.data.message, "success");
        loading.value = false;
        closeCreateGuild();
    } catch (e) {
        pushAlert(e.response.data.message, "error");
        loading.value = false;
        showCreateGuild.value = false;
    }
}

async function joinGuild(guild) {
    try {
        loading.value = true;
        let res = await axios.post("/join-guild", {
            guild: guild,
        });
        pushAlert(res.data.message, "success");
        myGuild();
        loading.value = false;
    } catch (e) {
        pushAlert(e.response.data.message, "error");
        loading.value = false;
    }
}
async function contributeGuild() {
    try {
        loading.value = true;
        let res = await axios.post("/contribute-guild", {
            contribute: createGuildForm.value.contribution,
        });
        pushAlert(res.data.message, "success");
        myGuild();
        loading.value = false;
        showOpenContribution.value = false;
    } catch (e) {
        pushAlert(e.response.data.message, "error");
        loading.value = false;
        showOpenContribution.value = false;
    }
}
async function myGuild() {
    try {
        loading.value = true;
        let res = await axios.get("/my-guild");
        guild.value = res.data.guild;
        loading.value = false;
    } catch (e) {
        pushAlert(e.response.data.message, "error");
        loading.value = false;
    }
}

async function getGuilds() {
    try {
        loading.value = true;
        let res = await axios.get("/get-guilds");
        guilds.value = res.data.guilds;
        loading.value = false;
    } catch (e) {
        pushAlert(e.response.data.message, "error");
        loading.value = false;
    }
}
function viewGuild(guild) {
    console.log(guild);
}

function leaveGuild() {
    try {
        loading.value = true;
        let res = axios.post("/leave-guild");
        pushAlert(res.data.message, "success");
        myGuild();
        loading.value = false;
    } catch (e) {
        pushAlert(e.response.data.message, "error");
        loading.value = false;
    }
}

onMounted(async () => {
    await getGuilds();
    await myGuild();
});
</script>

<template>
    <!-- ================= GAME LAYER (MOVEMENT SAFE) ================= -->
    <!-- NPC LAYER -->
    <div class="npc-layer">
        <div
            class="npc"
            :style="{ top: '140px', left: '470px' }"
            @click="isGuildOpen = true"
        >
            <img src="/npc/Guild.gif" class="npc-img" />

            <div class="npc-label">Guild Master</div>
        </div>
    </div>

    <!-- ================= UI LAYER ================= -->
    <div class="ui-layer" v-if="isGuildOpen">
        <div class="modal">
            <!-- HEADER -->
            <div class="header">
                <h1>Guild Hall</h1>

                <button @click="isGuildOpen = false">Close</button>
            </div>
            <!-- TABS -->
            <div class="tabs">
                <button
                    @click="activeTab = 'list'"
                    :class="{ active: activeTab === 'list' }"
                >
                    Guild List
                </button>

                <button
                    v-if="guild"
                    @click="activeTab = 'my'"
                    :class="{ active: activeTab === 'my' }"
                >
                    My Guild
                </button>

                <button v-if="!guild" @click="openCreateGuild">
                    Create Guild
                </button>
            </div>

            <!-- CONTENT -->
            <div class="content">
                <!-- LIST -->
                <div v-if="activeTab === 'list'" class="list">
                    <div
                        v-for="(guild, i) in guilds"
                        :key="guild.id"
                        class="flex items-center justify-between bg-gray-900 border border-gray-700 hover:border-yellow-500 transition rounded-lg px-4 py-3 mb-2 cursor-pointer"
                        @click="viewGuild(guild)"
                    >
                        <!-- LEFT SIDE -->
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-md bg-gray-800 border border-gray-700 overflow-hidden flex items-center justify-center cursor-pointer hover:opacity-80"
                            >
                                <img
                                    v-if="guild.icon"
                                    :src="guild.icon"
                                    alt="Guild Icon"
                                    class="w-full h-full object-cover"
                                />
                                <span v-else class="text-gray-500 text-xs">
                                    No Icon
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <div class="text-white font-semibold">
                                    {{ guild.name }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    Leader: {{ guild.leader?.name }}
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT SIDE -->
                        <div class="flex items-center gap-4">
                            <div class="text-right text-xs text-gray-400">
                                <div>Lv {{ guild.level }}</div>
                                <div class="text-yellow-400 font-semibold">
                                    Power: {{ guild.gold_stash }}
                                </div>
                            </div>

                            <!-- JOIN BUTTON -->
                            <button
                                v-if="!$page.props.auth.user.player?.guild_id"
                                class="px-3 py-1 text-xs rounded-md bg-green-600 hover:bg-green-500 text-white font-semibold transition"
                                @click.stop="joinGuild(guild)"
                            >
                                Join
                            </button>
                        </div>
                    </div>
                </div>

                <!-- MY GUILD -->
                <div v-else class="my space-y-3">
                    <!-- GUILD DETAILS -->
                    <div
                        v-if="guild"
                        class="box p-4 rounded-lg bg-gray-900 border border-gray-700"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <!-- LEFT: ICON + INFO -->
                            <div class="flex items-start gap-3">
                                <!-- GUILD ICON -->
                                <div
                                    class="w-12 h-12 rounded-md bg-gray-800 border border-gray-700 overflow-hidden flex items-center justify-center cursor-pointer hover:opacity-80"
                                    @click="triggerUpload"
                                >
                                    <img
                                        v-if="guild.icon"
                                        :src="guild.icon"
                                        alt="Guild Icon"
                                        class="w-full h-full object-cover"
                                    />
                                    <span v-else class="text-gray-500 text-xs">
                                        No Icon
                                    </span>

                                    <input
                                        ref="fileInput"
                                        type="file"
                                        class="hidden"
                                        accept="image/*"
                                        @change="handleUpload"
                                    />
                                </div>

                                <!-- TEXT INFO -->
                                <div>
                                    <div class="text-lg font-bold text-white">
                                        {{ guild.name }}
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        Leader: {{ guild.leader?.name }}
                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT STATS -->
                            <div class="text-right text-xs text-gray-400">
                                <div>Lv {{ guild.level }}</div>
                                <div>{{ guild?.members.length }} Members</div>
                                <div class="text-yellow-400 font-semibold">
                                    {{ guild.gold_stash }} Power
                                </div>
                            </div>
                        </div>

                        <!-- GLOBAL ACTION -->
                        <div class="flex justify-end mt-3">
                            <button
                                class="px-4 py-2 text-xs rounded-md bg-yellow-500 hover:bg-yellow-400 text-black font-semibold transition"
                                @click="showOpenContribution = true"
                            >
                                Contribute
                            </button>
                        </div>
                    </div>

                    <!-- MEMBERS SECTION -->
                    <div
                        v-if="guild"
                        class="box p-4 rounded-lg bg-gray-900 border border-gray-700"
                    >
                        <div class="text-white font-semibold mb-2">Members</div>

                        <div class="space-y-2">
                            <div
                                v-for="member in guild.members"
                                :key="member.id"
                                class="flex items-center justify-between bg-gray-800/60 hover:bg-gray-800 transition rounded-md px-3 py-2"
                            >
                                <!-- LEFT -->
                                <div class="flex flex-col">
                                    <div class="text-white font-medium">
                                        {{ member.player?.name }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        Lvl.{{ member?.player?.current_level }}
                                    </div>
                                    <div class="text-xs text-yellow-400">
                                        Contribution:
                                        {{ member?.gold_contribution }}
                                    </div>
                                </div>

                                <!-- RIGHT ACTION -->
                                <button
                                    v-if="
                                        $page.props.auth.user.player.id ===
                                        guild?.leader?.id
                                    "
                                    class="text-xs px-3 py-1 rounded-md bg-red-600 hover:bg-red-500 text-white font-semibold transition"
                                    @click="kickMember(member)"
                                >
                                    Kick
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- EMPTY STATE -->
                    <div v-else class="text-gray-400 text-center py-4">
                        You are not part of a guild.
                    </div>

                    <!-- LEAVE BUTTON -->
                    <div v-if="guild" class="flex justify-end">
                        <button
                            class="px-4 py-2 text-sm bg-red-600 hover:bg-red-500 text-white rounded-md transition"
                            @click="leaveGuild"
                        >
                            Leave Guild
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="create-modal" v-if="showCreateGuild">
        <div class="create-box">
            <h2>Create Guild</h2>

            <label>Guild Name</label>
            <input
                v-model.trim="createGuildForm.name"
                placeholder="Enter guild name"
            />

            <!-- PAYMENT TYPE BUTTONS -->
            <div class="payment-type-buttons">
                <button
                    class="choice-btn"
                    :class="{ active: createGuildForm.payment_type === 'gold' }"
                    @click="createGuildForm.payment_type = 'gold'"
                >
                    Gold
                </button>

                <button
                    class="choice-btn"
                    :class="{
                        active: createGuildForm.payment_type === 'diamond',
                    }"
                    @click="createGuildForm.payment_type = 'diamond'"
                >
                    Diamond
                </button>
            </div>

            <!-- COST -->
            <div class="cost">
                Cost:
                <span>
                    {{
                        createGuildForm.payment_type === "diamond"
                            ? createGuildDiamondCost
                            : createGuildCost
                    }}
                    {{
                        createGuildForm.payment_type === "diamond"
                            ? "Diamonds"
                            : "Gold"
                    }}
                </span>
            </div>

            <div class="actions">
                <button class="cancel" @click="closeCreateGuild">Cancel</button>

                <button
                    class="confirm"
                    @click="createGuild"
                    :disabled="!createGuildForm.name"
                >
                    Create
                </button>
            </div>
        </div>
    </div>

    <div class="create-modal" v-if="showOpenContribution && guild">
        <div class="create-box">
            <h2>Guild Contribution</h2>

            <label>Contribute for guild: {{ guild?.name }}</label>
            <input
                v-model="createGuildForm.contribution"
                placeholder="Enter amount"
            />

            <div class="actions">
                <button class="cancel" @click="showOpenContribution = false">
                    Cancel
                </button>

                <button class="confirm" @click="contributeGuild">
                    Contribute
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
