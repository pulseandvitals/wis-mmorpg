<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
const props = defineProps({
    classSkills: Object,
});

const user = usePage().props.auth.user;
const emit = defineEmits(["close"]);
</script>
<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl text-white font-bold">Skills</h1>

                <button
                    @click="$emit('close')"
                    class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-white"
                >
                    Close
                </button>
            </div>
            <div class="grid grid-cols-1 gap-2">
                <div
                    v-for="skill in classSkills"
                    :key="skill.name"
                    class="flex items-center gap-3 p-2 bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-md transition"
                >
                    <!-- ICON -->
                    <div
                        class="w-10 h-10 flex items-center justify-center bg-gray-900 border border-gray-600 rounded text-cyan-400 text-sm shrink-0"
                    >
                        <img :src="skill.icon_path" />
                    </div>

                    <!-- INFO -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <div
                                class="text-sm text-white font-semibold truncate"
                            >
                                {{ skill.name }}
                            </div>

                            <div
                                class="text-xs font-bold"
                                :class="
                                    user.player.current_level >=
                                    skill.requirement_level
                                        ? 'text-green-400'
                                        : 'text-red-400'
                                "
                            >
                                Lv {{ skill.requirement_level }}
                            </div>
                        </div>

                        <div class="text-[11px] text-gray-400 truncate">
                            {{ skill.description }}
                        </div>

                        <div class="flex gap-3 mt-1 text-[10px] uppercase">
                            <span class="text-gray-400">
                                Targets: [ {{ skill.target }} ]
                            </span>

                            <span
                                :class="{
                                    'text-red-500': skill.element === 'fire',
                                    'text-cyan-500': skill.element === 'water',
                                    'text-orange-500':
                                        skill.element === 'earth',
                                    'text-green-400': skill.element === 'wind',
                                    'text-yellow-400':
                                        skill.element === 'electric',
                                    'text-gray-300':
                                        skill.element === 'neutral',
                                }"
                            >
                                {{ skill.element }}
                            </span>

                            <span class="text-red-300">
                                {{ skill.damage }} DMG
                            </span>
                        </div>
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
