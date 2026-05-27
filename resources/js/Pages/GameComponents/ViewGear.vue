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
        return val ? JSON.parse(val) : null;
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
                ? Object.entries(basicStats).map(
                      ([k, v]) => `+${v} ${k.toUpperCase()}`,
                  )
                : [],

            random_stats: randomStats
                ? Object.entries(randomStats).map(
                      ([k, v]) => `+${v} ${k.toUpperCase()}`,
                  )
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
        <div class="relative bg-gray-900 rounded-xl p-4 min-w-[320px]">
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
                        class="w-20 h-20 bg-gray-800 border border-gray-700 rounded-lg flex flex-col items-center justify-center text-[10px]"
                    >
                        <img :src="item.icon" class="w-8 h-8 mb-1" />

                        <div class="text-white truncate w-full text-center">
                            {{ item.name }}
                        </div>

                        <div
                            v-if="item.enhancement_level"
                            class="text-purple-400"
                        >
                            {{ item.enhancement_level }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
