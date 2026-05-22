<script setup>
import { ref } from "vue";
import BetEvent from "./BetEvent.vue";
import TriviaEvent from "./TriviaEvent.vue";

const selectedEvent = ref();
const isBetEventOpen = ref(false);
const isTriviaEventOpen = ref(false);
/* =========================
EVENT LIST
========================= */
const events = ref([
    {
        id: 1,
        name: "Double EXP Weekend",
        description: "All monsters give 2x EXP.",
        status: "Active",
        color: "green",
        button: "Enter",
        bg: "/images/events/trivia.jpg",
    },

    {
        id: 2,
        name: "Golden Drop Event",
        description: "Rare items have increased drop rates.",
        status: "Upcoming",
        color: "yellow",
        button: "Enter",
        bg: "/images/events/trivia.jpg",
    },

    {
        id: 3,
        name: "PvP Tournament",
        description: "Compete against other players for rewards.",
        status: "Starting Soon",
        color: "red",
        button: "Enter",
        bg: "/images/events/trivia.jpg",
    },

    {
        id: 4,
        name: "Boss Raid",
        description: "World Boss appears every 2 hours.",
        status: "Active",
        color: "green",
        button: "Enter",
        bg: "/images/events/trivia.jpg",
    },
    {
        id: 5,
        name: "Bet 50/50",
        description:
            "Risk your gold in a 50/50 gamble event. Win to receive double rewards, or lose everything you placed.",
        status: "Daily",
        color: "green",
        button: "Bet",
        limit: 10,
        bg: "/images/events/trivia.jpg",
    },
    {
        id: 6,
        name: "Trivia",
        description:
            "Answer random trivia questions correctly to earn gold, rewards, and exclusive event prizes.",
        status: "Daily",
        color: "green",
        button: "Answer",
        limit: 10,
        bg: "/images/events/trivia.jpg",
    },
]);
function enterEvent(event) {
    switch (event.id) {
        case 5:
            isBetEventOpen.value = true;
            break;
        case 6:
            isTriviaEventOpen.value = true;
            break;
        default:
    }
}
function badgeClass(color) {
    return {
        green: "bg-green-500/20 text-green-400 border-green-500/30",
        yellow: "bg-yellow-500/20 text-yellow-400 border-yellow-500/30",
        red: "bg-red-500/20 text-red-400 border-red-500/30",
    }[color];
}
</script>

<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl text-white font-bold tracking-wide">
                    Event List
                </h1>

                <button
                    @click="$emit('close')"
                    class="px-4 py-2 rounded-lg text-sm font-semibold bg-red-500/90 hover:bg-red-400 border border-red-300/20 text-white transition"
                >
                    Close
                </button>
            </div>

            <!-- EVENT LOOP -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div
                    v-for="event in events"
                    :key="event.id"
                    class="event-card"
                    :style="{
                        backgroundImage: `url(${event.bg})`,
                    }"
                >
                    <!-- OVERLAY -->
                    <div class="event-overlay"></div>

                    <!-- CONTENT -->
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-3">
                            <h2
                                class="text-2xl font-bold text-white drop-shadow"
                            >
                                {{ event.name }}
                            </h2>

                            <span
                                :class="badgeClass(event.color)"
                                class="px-3 py-1 rounded-full text-xs border backdrop-blur-sm"
                            >
                                {{ event.status }}
                            </span>
                        </div>

                        <p class="text-gray-200 text-sm mb-5 leading-relaxed">
                            {{ event.description }}
                        </p>

                        <!-- BUTTON -->
                        <button class="event-button" @click="enterEvent(event)">
                            {{ event.button ?? "Enter Event" }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <BetEvent v-if="isBetEventOpen" @close="isBetEventOpen = false" />
    <TriviaEvent v-if="isTriviaEventOpen" @close="isTriviaEventOpen = false" />
</template>

<style scoped>
.npc-modal-content {
    width: 100%;
    max-width: 1000px;
    min-height: 500px;

    background: #111827;

    border-radius: 24px;

    border: 1px solid rgba(255, 255, 255, 0.1);

    position: relative;

    z-index: 999999999;
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

.event-card {
    position: relative;

    min-height: 100px;

    padding: 15px;

    border-radius: 18px;

    overflow: hidden;

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;

    border: 1px solid rgba(255, 255, 255, 0.08);

    transition: 0.2s ease;
}

.event-card:hover {
    transform: translateY(-2px);

    border-color: rgba(255, 255, 255, 0.18);
}

.event-overlay {
    position: absolute;
    inset: 0;

    background: linear-gradient(
        to top,
        rgba(15, 23, 42, 0.95),
        rgba(15, 23, 42, 0.55)
    );
}

/* BUTTON */ /* MMORPG STYLE COMPACT BUTTON */
.event-button {
    position: relative;

    padding: 8px 18px;

    border-radius: 9px;

    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.3px;

    color: #f8fafc;

    background: linear-gradient(to bottom, #2563eb, #1d4ed8);

    border: 1px solid rgba(255, 255, 255, 0.08);

    box-shadow:
        inset 0 1px 0 rgba(255, 255, 255, 0.12),
        0 2px 10px rgba(37, 99, 235, 0.22);

    transition: all 0.18s ease;

    overflow: hidden;
}

/* subtle top shine */
.event-button::before {
    content: "";

    position: absolute;
    top: 0;
    left: 0;
    right: 0;

    height: 50%;

    background: rgba(255, 255, 255, 0.06);

    pointer-events: none;
}

.event-button:hover {
    background: linear-gradient(to bottom, #3b82f6, #2563eb);

    transform: translateY(-1px);

    box-shadow:
        inset 0 1px 0 rgba(255, 255, 255, 0.16),
        0 4px 14px rgba(59, 130, 246, 0.28);
}

.event-button:active {
    transform: scale(0.97);
}
</style>
