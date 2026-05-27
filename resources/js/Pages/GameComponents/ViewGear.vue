<script setup>
import { computed } from "vue";

const props = defineProps({
    gear: {
        type: Object,
        default: () => ({}),
    },
});

const safeParse = (val) => {
    try {
        return typeof val === "string" ? JSON.parse(val) : val || null;
    } catch (e) {
        return null;
    }
};

const EQUIP_SLOTS = [
    { key: "helmet", label: "Helmet", folder: "gears" },
    { key: "weapon", label: "Weapon", folder: "gears" },
    { key: "armor", label: "Armor", folder: "gears" },
    { key: "boots", label: "Boots", folder: "gears" },
    { key: "gloves", label: "Gloves", folder: "gears" },
    { key: "shield", label: "Shield", folder: "gears" },
    { key: "pants", label: "Pants", folder: "gears" },
    { key: "ring", label: "Ring", folder: "gears" },
    { key: "wing", label: "Wing", folder: "gears" },
];

const armory = computed(() =>
    EQUIP_SLOTS.map((s) => {
        const equip = props.gear?.[s.key];

        const gear = equip?.gear;

        const basicStats = safeParse(gear?.basic_stats);
        const randomStats = safeParse(equip?.random_stat);

        return {
            key: s.key,
            slot: s.label,

            icon: gear ? `/${s.folder}/${gear.name}.png` : "/empty.png",

            name: gear?.name || `Empty ${s.label} Slot`,

            description: gear?.requirement_level
                ? `Level ${gear.requirement_level} ${s.label}`
                : `No ${s.label.toLowerCase()} equipped`,

            enhancement_level: equip?.enhancement_level
                ? `+${equip.enhancement_level}`
                : "",

            stats: basicStats
                ? Object.entries(basicStats).map(([k, v]) => ({
                      key: k,
                      value: v,
                  }))
                : [],

            random_stats: randomStats
                ? Object.entries(randomStats).map(([k, v]) => ({
                      key: k,
                      value: v,
                  }))
                : [],
        };
    }),
);

const CARD_SLOTS = [
    { key: "card_1", label: "Card 1", folder: "cards" },
    { key: "card_2", label: "Card 2", folder: "cards" },
    { key: "card_3", label: "Card 3", folder: "cards" },
    { key: "card_4", label: "Card 4", folder: "cards" },
];

const cardArmory = computed(() =>
    CARD_SLOTS.map((s) => {
        const equip = props.gear?.[s.key];
        const card = equip?.card;

        const effects = safeParse(card?.effects);

        return {
            key: s.key,
            slot: s.label,

            icon: card ? `/${s.folder}/${card.name}.png` : "/empty.png",

            name: card?.name || "Empty Card Slot",

            description: card ? card?.description : "No card equipped",

            stats: effects
                ? Object.entries(effects).map(([k, v]) => ({
                      key: k,
                      value: v,
                  }))
                : [],
        };
    }),
);
</script>

<template>
    <!-- overlay -->
    <div
        class="fixed inset-0 bg-black/70 flex items-center justify-center z-50"
    >
        <!-- modal -->
        <div
            class="relative bg-gray-900 rounded-xl p-4 min-w-[320px] max-h-[90vh]"
        >
            <!-- close button -->
            <button
                @click="$emit('close')"
                class="absolute top-2 right-2 text-gray-300 hover:text-red-500 text-lg"
            >
                ✕
            </button>

            <!-- player name -->
            <div class="text-center mb-3">
                <div class="text-purple-400 text-sm">Player</div>

                <div class="text-white text-lg font-bold">
                    {{ gear?.name || "Unknown Player" }}
                </div>
            </div>

            <!-- gear grid -->
            <div class="flex justify-center">
                <div class="grid grid-cols-3 gap-2">
                    <div
                        v-for="item in armory"
                        :key="item.key"
                        class="group relative w-20 h-20 bg-gray-800 border border-gray-700 hover:border-yellow-500 rounded-lg flex flex-col items-center justify-center text-[10px] transition-all"
                    >
                        <img :src="item.icon" class="w-8 h-8 mb-1" />

                        <div
                            class="text-white truncate w-full text-center px-1"
                        >
                            {{ item.name }}
                        </div>

                        <div
                            v-if="item.enhancement_level"
                            class="text-purple-400"
                        >
                            {{ item.enhancement_level }}
                        </div>

                        <!-- tooltip -->
                        <div
                            class="absolute hidden group-hover:block z-50 left-1/2 -translate-x-1/2 top-full mt-2 w-52 bg-black border border-gray-700 rounded-lg p-3 text-xs shadow-xl"
                        >
                            <div class="text-yellow-400 font-bold text-sm mb-1">
                                {{ item.name }}
                            </div>

                            <div class="text-gray-400 mb-2">
                                {{ item.description }}
                            </div>

                            <!-- BASIC STATS -->
                            <div
                                v-if="item.stats.length"
                                class="mb-2 border-t border-gray-700 pt-2"
                            >
                                <div class="text-blue-400 font-semibold mb-1">
                                    Basic Stats
                                </div>

                                <div
                                    v-for="stat in item.stats"
                                    :key="stat.key"
                                    class="text-white"
                                >
                                    +{{ stat.value }}
                                    {{ stat.key.toUpperCase() }}
                                </div>
                            </div>

                            <!-- RANDOM STATS -->
                            <div
                                v-if="item.random_stats.length"
                                class="border-t border-gray-700 pt-2"
                            >
                                <div class="text-green-400 font-semibold mb-1">
                                    Random Stats
                                </div>

                                <div
                                    v-for="stat in item.random_stats"
                                    :key="stat.key"
                                    class="text-green-300"
                                >
                                    +{{ stat.value }}
                                    {{ stat.key.toUpperCase() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-purple-400 text-center mb-2 font-bold">
                    Equipped Cards
                </div>

                <div class="flex justify-center">
                    <div class="grid grid-cols-4 gap-2">
                        <div
                            v-for="card in cardArmory"
                            :key="card.key"
                            class="group relative w-20 h-20 bg-gray-800 border border-gray-700 hover:border-purple-500 rounded-lg flex flex-col items-center justify-center text-[10px] transition-all"
                        >
                            <img :src="card.icon" class="w-8 h-8 mb-1" />

                            <div
                                class="text-white truncate w-full text-center px-1"
                            >
                                {{ card.name }}
                            </div>

                            <!-- TOOLTIP -->
                            <div
                                class="absolute hidden group-hover:block z-[9999] left-1/2 -translate-x-1/2 top-full mt-2 w-52 bg-black border border-gray-700 rounded-lg p-3 text-xs shadow-xl"
                            >
                                <div
                                    class="text-purple-400 font-bold text-sm mb-1"
                                >
                                    {{ card?.name }}
                                </div>

                                <div class="text-gray-400 mb-2">
                                    {{ card.description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
