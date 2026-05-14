<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
const props = defineProps({
    npc: Object,
    player: Object,
});

async function healPlayer() {
    try {
        const response = await axios.post("/heal");
        Object.assign(props.player, response.data.player);
    } catch (error) {
        console.error(error);
    }
}
</script>
<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl text-white font-bold tracking-wide">
                    {{ npc.name }}
                </h1>

                <button
                    @click="$emit('close')"
                    class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-white transition"
                >
                    Close
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 rounded-2xl border border-gray-500 p-6">
                    <!-- HEADER -->
                    <div class="flex items-center gap-4 mb-5">
                        <div
                            class="w-16 h-16 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-3xl"
                        >
                            ✨
                        </div>

                        <div>
                            <p class="text-2xl font-bold text-white">
                                Keeper of Light and Restoration
                            </p>
                        </div>
                    </div>

                    <!-- DIALOGUE -->
                    <div class="text-gray-200 text-sm leading-7 space-y-4">
                        <p>
                            Ah... another weary soul arrives at the sanctuary. I
                            can sense the weight of battle upon your body, and
                            the exhaustion carried deep within your spirit.
                        </p>

                        <p>
                            The lands beyond the Town Square have grown far more
                            dangerous than they once were. Monsters wander
                            without restraint, ancient creatures awaken beneath
                            forgotten ruins, and darkness slowly spreads through
                            the realm.
                        </p>

                        <p>
                            Many adventurers pass through these halls seeking
                            strength, yet only a few understand the true cost of
                            endless battle. Wounds may fade with time, but a
                            shattered spirit is far more difficult to restore.
                        </p>

                        <p>
                            Rest here for a moment, traveler. Allow the sacred
                            light to mend your injuries, restore your mana, and
                            calm your weary heart. No warrior should continue
                            their journey burdened by pain.
                        </p>

                        <p class="italic text-gray-300">
                            “May the light guide your path and protect you from
                            the shadows ahead.”
                        </p>
                    </div>

                    <!-- ACTIONS -->
                    <div class="mt-8 flex items-center gap-4">
                        <button
                            class="px-8 py-3 rounded-xl bg-green-500/60 hover:bg-green-500/10 transition text-white font-bold border border-white/10"
                            @click="healPlayer"
                        >
                            ✨ Heal Me
                        </button>

                        <span class="text-xs text-gray-400">
                            Fully restores HP and MP
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
/* MODAL BOX */
.npc-modal-content {
    width: 100%;
    max-width: 900px;
    min-height: 400px;

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
