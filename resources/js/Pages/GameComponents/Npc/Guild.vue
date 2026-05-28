<script setup>
import { pushAlert } from "@/Stores/GlobalAlert";
import { ref } from "vue";

const isGuildOpen = ref(false);
const activeTab = ref("list");

const guilds = ref([
    {
        id: 1,
        name: "Crimson Order",
        leader_name: "Aldric",
        level: 12,
        members_count: 34,
        power: 9850,
    },
    {
        id: 2,
        name: "Shadow Hunters",
        leader_name: "Luna",
        level: 18,
        members_count: 52,
        power: 15240,
    },
]);

const myGuild = ref({
    id: 2,
    name: "Shadow Hunters",
    leader_name: "Luna",
    level: 18,
    members_count: 52,
    power: 15240,
});
const showCreateGuild = ref(false);
const createGuildCost = 500000;
const loading = ref(false);
const guild = ref(null);
const createGuildForm = ref({
    name: "",
});

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
function viewGuild(guild) {
    console.log(guild);
}

function leaveGuild() {
    myGuild.value = null;
}
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
                        class="row"
                        @click="viewGuild(guild)"
                    >
                        <div class="left">
                            <div class="rank">#{{ i + 1 }}</div>
                            <div>
                                <div class="name">{{ guild.name }}</div>
                                <div class="sub">
                                    Leader: {{ guild.leader_name }}
                                </div>
                            </div>
                        </div>

                        <div class="right">
                            <div>Lv {{ guild.level }}</div>
                            <div class="sub">{{ guild.power }}</div>
                        </div>
                    </div>
                </div>

                <!-- MY GUILD -->
                <div v-else class="my">
                    <div v-if="guild" class="box">
                        <div class="title">{{ guild.name }}</div>
                        <div class="sub">Leader: {{ guild.leader_id }}</div>

                        <div class="stats">
                            <div>Lv {{ guild.level }}</div>
                            <div>{{ guild?.members }} Members</div>
                            <div>{{ guild.guild_contribution }} Power</div>
                        </div>
                    </div>

                    <div v-else class="empty">You are not part of a guild.</div>

                    <button v-if="guild" class="leave" @click="leaveGuild">
                        Leave Guild
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="create-modal" v-if="showCreateGuild">
        <div class="create-box">
            <h2>Create Guild</h2>

            <label>Guild Name</label>
            <input
                v-model="createGuildForm.name"
                placeholder="Enter guild name"
            />
            <div class="cost">
                Cost: <span>{{ createGuildCost }} Gold</span>
            </div>

            <div class="actions">
                <button class="cancel" @click="closeCreateGuild">Cancel</button>

                <button class="confirm" @click="createGuild">Create</button>
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
</style>
