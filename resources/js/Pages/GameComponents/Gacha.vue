<template>
    <div
        class="fixed inset-0 bg-black/80 flex items-center justify-center z-50"
    >
        <div
            class="relative w-[420px] bg-gradient-to-b from-gray-900 to-black border border-purple-600 rounded-2xl p-6 shadow-[0_0_40px_rgba(168,85,247,0.5)]"
        >
            <!-- Close -->
            <button
                @click="close"
                class="absolute top-2 right-3 text-white hover:text-red-400 text-xl"
            >
                ✕
            </button>

            <!-- Title -->
            <h2
                class="text-center text-purple-300 font-bold tracking-widest text-lg mb-4"
            >
                ✨ Wheel of Fate Gacha ✨
            </h2>

            <!-- Pointer -->
            <div class="flex justify-center relative">
                <div class="absolute -top-2 z-20">
                    <div
                        class="w-0 h-0 border-l-[12px] border-r-[12px] border-t-[20px] border-l-transparent border-r-transparent border-t-red-500 drop-shadow-lg"
                    ></div>
                </div>

                <!-- Wheel -->
                <div
                    ref="wheel"
                    class="w-[280px] h-[280px] rounded-full border-4 border-purple-500 relative overflow-hidden transition-transform duration-[4000ms] ease-out"
                    :class="{ 'animate-glow': spinning }"
                    :style="wheelStyle"
                >
                    <!-- Labels -->
                    <div
                        v-for="(item, i) in items"
                        :key="i"
                        class="absolute inset-0 flex items-center justify-center pointer-events-none"
                        :style="labelStyle(i)"
                    >
                        <span
                            class="absolute text-[10px] font-bold text-white w-[70px] text-center drop-shadow-lg"
                            style="transform: translateY(-120px)"
                        >
                            {{ item.name }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Spin Button -->
            <button
                @click="spin"
                :disabled="spinning"
                class="mt-6 w-full py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg disabled:opacity-50"
            >
                {{ spinning ? "Spinning..." : "SPIN ✨" }}
            </button>

            <!-- Result -->
            <p
                v-if="result"
                class="text-center mt-4 text-yellow-300 font-semibold"
            >
                🎉 You got: {{ result.name }} ({{ result.rarity }})
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";

const wheel = ref(null);
const spinning = ref(false);
const rotation = ref(0);
const result = ref(null);

const items = [
    { name: "Common Sword", rarity: "R" },
    { name: "Rare Bow", rarity: "SR" },
    { name: "Epic Staff", rarity: "SR" },
    { name: "Potion", rarity: "R" },
    { name: "Rare Armor", rarity: "SR" },
    { name: "Dragon Blade", rarity: "SSR" },
    { name: "Ring", rarity: "R" },
    { name: "Shadow Dagger", rarity: "SR" },
    { name: "Wings", rarity: "SSR" },
    { name: "Boots", rarity: "R" },
    { name: "Magic Tome", rarity: "SR" },
    { name: "Phoenix Blade", rarity: "SSR" },
];

const segment = 360 / items.length;

/* ✅ CRITICAL FIX:
   Align wheel so index 0 starts at TOP */
const wheelStyle = computed(() => {
    return {
        background: `
        conic-gradient(
            from -90deg,
            #a855f7 0deg 30deg,
            #1f2937 30deg 60deg,
            #a855f7 60deg 90deg,
            #1f2937 90deg 120deg,
            #a855f7 120deg 150deg,
            #1f2937 150deg 180deg,
            #a855f7 180deg 210deg,
            #1f2937 210deg 240deg,
            #a855f7 240deg 270deg,
            #1f2937 270deg 300deg,
            #a855f7 300deg 330deg,
            #1f2937 330deg 360deg
        )
        `,
    };
});

/* label positioning */
function labelStyle(i) {
    return {
        transform: `rotate(${i * segment}deg)`,
    };
}

function spin() {
    if (spinning.value) return;

    spinning.value = true;
    result.value = null;

    const index = Math.floor(Math.random() * items.length);

    const extraSpins = 6 * 360;

    /* ✅ CENTER OF SLICE */
    const sliceCenter = index * segment + segment / 2;

    /* ✅ FINAL CORRECT ROTATION (NO OFFSETS EVER AGAIN) */
    const finalRotation = extraSpins + (360 - sliceCenter);

    rotation.value += finalRotation;

    wheel.value.style.transform = `rotate(${rotation.value}deg)`;

    setTimeout(() => {
        result.value = items[index];
        spinning.value = false;
    }, 4200);
}

function close() {
    spinning.value = false;
    result.value = null;
}
</script>

<style>
@keyframes glow {
    0% {
        box-shadow: 0 0 10px rgba(168, 85, 247, 0.4);
    }
    50% {
        box-shadow: 0 0 30px rgba(168, 85, 247, 0.9);
    }
    100% {
        box-shadow: 0 0 10px rgba(168, 85, 247, 0.4);
    }
}

.animate-glow {
    animation: glow 1.5s infinite;
}
</style>
