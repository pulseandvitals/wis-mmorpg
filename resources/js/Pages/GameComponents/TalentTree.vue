<template>
    <div
        class="fixed inset-0 bg-black/70 flex items-center justify-center z-50"
    >
        <div
            class="w-[520px] rounded-xl bg-gray-900 border border-gray-700 text-white flex flex-col"
        >
            <!-- HEADER -->
            <div
                class="flex items-center justify-between px-4 py-3 border-b border-gray-800"
            >
                <h2 class="text-sm font-bold">Talent Tree</h2>

                <button
                    @click="$emit('close')"
                    class="text-gray-400 hover:text-white text-sm"
                >
                    ✕
                </button>
            </div>
            <!-- BODY -->
            <div class="p-4">
                <!-- LOCKED -->
                <div
                    class="text-center py-6"
                    v-if="playerData?.current_level < 30"
                >
                    <div class="text-red-400 font-bold text-lg mb-2">
                        Locked
                    </div>

                    <p class="text-xs text-gray-400">
                        Unlock at <span class="text-yellow-400">Level 30</span>
                    </p>

                    <div class="mt-2 text-xs text-gray-500">
                        Current Level: {{ playerData?.current_level }}
                    </div>
                </div>

                <!-- UNLOCKED -->
                <div>
                    <p class="text-xs text-gray-400 mb-3">
                        Choose
                        <span class="text-white font-semibold">3 talents</span>
                    </p>

                    <!-- GRID 2 COLUMNS -->
                    <div class="grid grid-cols-2 gap-2">
                        <div
                            v-for="(talent, index) in talents"
                            :key="talent.id"
                            :class="[
                                'flex items-center justify-between border rounded-lg p-2 transition',
                                selected.includes(index) ||
                                activeTalentIds.includes(talent.id)
                                    ? 'bg-gray-800 border-green-500'
                                    : 'bg-gray-800/40 border-gray-700 opacity-50 grayscale',
                            ]"
                        >
                            <!-- LEFT -->
                            <div class="flex items-center gap-2 min-w-0">
                                <img
                                    :src="`/talents/${talent.name}.png`"
                                    class="w-8 h-8 rounded bg-gray-700 border border-gray-600 flex-shrink-0"
                                />

                                <div class="min-w-0">
                                    <div class="text-xs font-semibold truncate">
                                        {{ talent.name }}
                                    </div>

                                    <div
                                        class="text-[10px] text-gray-400 truncate"
                                    >
                                        {{ talent.description }}
                                    </div>
                                </div>
                            </div>

                            <!-- BUTTON -->
                            <button
                                v-if="
                                    selected.length < 3 ||
                                    selected.includes(index) ||
                                    activeTalentIds.includes(talent.id)
                                "
                                class="text-[10px] px-2 py-1 rounded bg-green-600 hover:bg-green-500 flex-shrink-0"
                                :disabled="
                                    (selected.length >= 3 &&
                                        !selected.includes(index)) ||
                                    activeTalentIds.includes(talent.id)
                                "
                                @click="toggleTalent(talent)"
                            >
                                {{
                                    selected.includes(index) ||
                                    activeTalentIds.includes(talent.id)
                                        ? "✓"
                                        : "+"
                                }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FOOTER -->
            <div
                class="px-4 py-3 border-t border-gray-800 flex items-center justify-between"
            >
                <div class="text-xs text-gray-400">
                    Selected: {{ activeTalentIds.length || selected.length }}/3
                </div>

                <div class="flex gap-2">
                    <button
                        v-if="playerData?.selected_talent_skills"
                        class="px-3 py-1 text-xs rounded bg-red-600 hover:bg-red-500 disabled:opacity-50"
                        @click="resetSelection"
                    >
                        Reset talents
                    </button>
                    <button
                        v-if="
                            selected.length > 0 &&
                            !playerData.selected_talent_skills
                        "
                        class="px-3 py-1 text-xs rounded bg-red-600 hover:bg-red-500 disabled:opacity-50"
                        @click="resetSelectedTalents"
                    >
                        Reset
                    </button>
                    <button
                        v-if="!playerData.selected_talent_skills"
                        class="px-3 py-1 text-xs rounded bg-blue-600 hover:bg-blue-500 disabled:opacity-50"
                        :disabled="
                            selected.length !== 3 ||
                            playerData?.current_level < 30
                        "
                        @click="confirmSelection"
                    >
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { pushAlert } from "@/Stores/GlobalAlert";
import { computed, onMounted, ref } from "vue";
import { router } from "@inertiajs/vue3";
const props = defineProps({
    player: Object,
});

const emit = defineEmits(["close", "confirm"]);

const selected = ref([]);
const talents = ref([]);
const loading = ref(false);
const playerData = ref({
    current_level: props.player.current_level,
    selected_talent_skills: props.player.selected_talent_skills,
});
async function getTalents() {
    try {
        loading.value = true;
        const res = await axios.get("/get-talents");
        talents.value = res.data;
        loading.value = false;
    } catch (e) {
        pushAlert("Failed to load talents", "error");
        loading.value = false;
    }
}

computed(() => {
    return talents.value.map((talent) => ({
        ...talent,
        effects: JSON.parse(talent.effects),
    }));
});

function toggleTalent(talent) {
    if (selected.value.includes(talent)) {
        selected.value = selected.value.filter((t) => t !== talent);
    } else {
        if (selected.value.length < 3) {
            selected.value.push(talent);
        }
    }
}

const parsedSelectedEffects = computed(() => {
    return (playerData.value.selected_talent_skills || [])
        .map((item) => {
            try {
                return JSON.parse(item); // first decode string -> array
            } catch (e) {
                return [];
            }
        })
        .flat();
});

const activeTalentIds = computed(() => {
    const effects = parsedSelectedEffects.value;
    if (playerData.value.selected_talent_skills?.length === 0) return [];
    return talents.value
        .filter((talent) => {
            const talentEffects = JSON.parse(talent.effects || "[]");

            return effects.some((selected) =>
                talentEffects.some((t) => t.stat === selected.stat),
            );
        })
        .map((t) => t.id);
});

function resetSelectedTalents() {
    selected.value = [];
}
async function resetSelection() {
    const cost = 499;

    if (
        confirm(
            `Are you sure you want to reset your talents? This will cost ${cost} diamonds.`,
        )
    ) {
        try {
            loading.value = true;
            const res = await axios.post("/reset-talents", {
                cost,
            });
            playerData.value.selected_talent_skills = structuredClone(
                res.data.player.selected_talent_skills,
            );
            selected.value = [];
            pushAlert(res.data.message || "Talents reset!", "success");
            loading.value = false;
        } catch (e) {
            pushAlert(
                e.response?.data?.message || "Failed to reset talents.",
                "error",
            );
            loading.value = false;
        }
    }
}

async function confirmSelection() {
    try {
        loading.value = true;
        const res = await axios.post("/store-selected-talents", {
            talents: selected.value.map((t) => t.id),
        });
        console.log("incoming player:", res.data.player);
        console.log("before update:", playerData.value);
        pushAlert(res.data.message || "Talents updated!", "success");
        playerData.value = structuredClone(res.data.player);
        loading.value = false;
    } catch (e) {
        pushAlert(
            e.response?.data?.message || "Failed to update talents.",
            "error",
        );
        loading.value = false;
    }
}
onMounted(() => {
    getTalents();
});
</script>
