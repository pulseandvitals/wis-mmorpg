<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl text-white font-bold tracking-wide">
                    Party Room
                </h1>

                <button
                    @click="$emit('close')"
                    class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-white transition"
                >
                    Close
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- MAIN PANEL -->
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
                                👥
                            </div>

                            <div>
                                <p class="text-sm text-gray-400 mt-1">
                                    Gather up to 3 adventurers
                                </p>
                            </div>
                        </div>

                        <!-- ROOM INFO -->
                        <div class="text-right">
                            <p class="text-xs text-gray-400">Room ID</p>
                            <p class="text-white font-semibold">
                                {{ room?.code ?? "-" }}
                            </p>
                        </div>
                    </div>

                    <!-- ACTIONS -->
                    <div class="flex gap-3 mb-6" v-if="!room?.members">
                        <button
                            @click="createRoom"
                            class="px-4 py-2 rounded-lg bg-purple-500/20 border border-purple-400/30 text-purple-300 hover:bg-purple-500/30 transition"
                        >
                            ➕ Create Room
                        </button>

                        <input
                            v-model="code"
                            placeholder="Enter Room ID"
                            class="flex-1 bg-black/60 border border-white/10 text-white rounded-xl px-4 py-2 outline-none focus:border-purple-400/60"
                        />

                        <button
                            @click="joinRoom"
                            class="px-4 py-2 rounded-lg bg-pink-500/20 border border-pink-400/30 text-pink-300 hover:bg-pink-500/30 transition"
                        >
                            Join
                        </button>
                    </div>

                    <!-- MEMBERS -->
                    <div class="space-y-3 mb-6">
                        <div class="text-xs text-gray-400 mb-2">
                            Members ({{ room?.members?.length ?? 0 }}/3)
                        </div>

                        <div
                            v-for="member in room?.members"
                            :key="member.id"
                            class="flex items-center justify-between rounded-xl border border-white/10 bg-black/20 hover:bg-purple-500/5 transition px-5 py-4"
                        >
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-lg font-bold text-purple-300"
                                >
                                    👤
                                </div>

                                <div>
                                    <p class="text-white font-medium">
                                        {{ member.player.name }}
                                    </p>

                                    <p class="text-xs text-gray-400">
                                        {{ member.player.class_type }}
                                    </p>
                                </div>
                            </div>

                            <button
                                v-if="member.player_id === playerId"
                                @click="leaveRoom"
                                class="text-xs px-3 py-1 rounded-lg bg-red-500/20 border border-red-400/30 text-red-300 hover:bg-red-500/30"
                            >
                                Leave
                            </button>
                        </div>

                        <div
                            v-if="!room?.members?.length"
                            class="text-gray-500 text-sm"
                        >
                            No adventurers yet...
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div
                        class="pt-4 border-t border-white/10 flex items-center justify-between"
                    >
                        <p class="text-xs text-gray-500">
                            “Bound by oath, the party shares 10% of all
                            experience gained in battle.”
                        </p>

                        <div class="flex gap-2">
                            <span class="text-xs text-gray-400"> Status: </span>

                            <span class="text-xs text-purple-300">
                                {{ room ? "In Room" : "Idle" }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import axios from "axios";
import { useForm, usePage } from "@inertiajs/vue3";
import { pushAlert } from "@/Stores/GlobalAlert";
const room = ref(null);
const code = ref("");
const page = usePage();

const playerId = page.props.auth.user.player.id;

async function createRoom() {
    const res = await axios.post("/party-room/create");
    room.value = res.data;
    pushAlert("Created a party", "success");
}
async function getMyRoom() {
    const res = await axios.get("/get-party");
    room.value = res.data;
}

async function joinRoom() {
    try {
        const res = await axios.post(`/party-room/join/${code.value}`);
        room.value = res.data;
        pushAlert("Joined a party", "success");
    } catch (err) {
        console.log("JOIN ERROR:", err.response?.data || err);
    }
}

async function leaveRoom() {
    const res = await axios.post(`/party-room/leave/${room.value.id}`);
    room.value = res.data;
    pushAlert("You leave a party", "warning");
}

onMounted(async () => {
    await getMyRoom(); // 1. load data first
});
</script>

<style scoped>
.npc-modal-content {
    width: 100%;
    max-width: 900px;
    min-height: 450px;

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
