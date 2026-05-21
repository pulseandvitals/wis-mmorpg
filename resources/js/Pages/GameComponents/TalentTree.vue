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
                <div class="text-center py-6">
                    <div class="text-red-400 font-bold text-lg mb-2">
                        Locked
                    </div>

                    <p class="text-xs text-gray-400">
                        Unlock at <span class="text-yellow-400">Level 30</span>
                    </p>

                    <div class="mt-2 text-xs text-gray-500">
                        Current Level: {{ player.current_level }}
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
                            :key="index"
                            :class="[
                                'flex items-center justify-between border rounded-lg p-2 transition',
                                selected.includes(index)
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
                                    selected.length >= 3 &&
                                    !selected.includes(index)
                                "
                                class="text-[10px] px-2 py-1 rounded bg-green-600 hover:bg-green-500 flex-shrink-0"
                                :disabled="
                                    selected.length >= 3 &&
                                    !selected.includes(index)
                                "
                                @click="toggleTalent(index)"
                            >
                                {{ selected.includes(index) ? "✓" : "+" }}
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
                    Selected: {{ selected.length }}/3
                </div>

                <div class="flex gap-2">
                    <button
                        class="px-3 py-1 text-xs rounded bg-red-600 hover:bg-red-500 disabled:opacity-50"
                        :disabled="
                            selected.length !== 3 || player.current_level < 30
                        "
                        @click="confirmSelection"
                    >
                        Reset
                    </button>

                    <button
                        class="px-3 py-1 text-xs rounded bg-blue-600 hover:bg-blue-500 disabled:opacity-50"
                        :disabled="
                            selected.length !== 3 || player.current_level < 30
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
import { ref } from "vue";

const props = defineProps({
    player: Object,
});

const emit = defineEmits(["close", "confirm"]);

const selected = ref([]);

const talents = ref([
    {
        name: "Power Strike",
        description: "+10% Attack damage",
    },
    {
        name: "Iron Skin",
        description: "+12% Defense",
    },
    {
        name: "Swift Step",
        description: "+15% Movement Speed",
    },
    {
        name: "Critical Focus",
        description: "+8% Critical Rate",
    },
    {
        name: "Evasive Dance",
        description: "+8% Evasion Rate",
    },
    {
        name: "Mana Flow",
        description: "+20% Mana increase",
    },
    {
        name: "Fisherman",
        description: "+15% Catching Chance",
    },
    {
        name: "Bet Collector",
        description: "+10% Betting Success Rate",
    },
]);

function toggleTalent(index) {
    if (selected.value.includes(index)) {
        selected.value = selected.value.filter((i) => i !== index);
    } else {
        if (selected.value.length < 3) {
            selected.value.push(index);
        }
    }
}

function resetSelection() {
    selected.value = [];
}

function confirmSelection() {
    emit("confirm", selected.value);
    emit("close");
}
</script>
