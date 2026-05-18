<script setup>
import { pushAlert } from "@/Stores/GlobalAlert";
import { useForm, usePage } from "@inertiajs/vue3";
const props = defineProps({
    all_maps: Object,
    npc: Object,
});
const form = useForm({});
const user = usePage().props.auth.user;
const emit = defineEmits(["close"]);

function goTo(map_id) {
    form.get(route("world.map", map_id), {
        onStart: () => emit("close"),
        onError: (errors) => {
            pushAlert(errors.message || "Level not met.", "error");
        },
    });
}
</script>
<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl text-white font-bold">
                    {{ npc.name }}
                </h1>

                <button
                    @click="$emit('close')"
                    class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-white"
                >
                    Close
                </button>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div
                    v-for="map in all_maps"
                    :key="map.map_id"
                    class="p-4 bg-gray-800 hover:bg-gray-700 rounded-lg border border-gray-600 cursor-pointer transition"
                    :class="
                        map.map_id === user.player.current_map_id
                            ? 'bg-gray-900 opacity-40 cursor-not-allowed'
                            : ''
                    "
                    @click="goTo(map.map_id)"
                >
                    <!-- IMAGE -->
                    <img
                        :src="`/maps/${map.name}.png`"
                        class="w-full h-24 object-cover rounded-md mb-2 border border-gray-700"
                    />

                    <!-- NAME -->
                    <div class="text-lg text-white">
                        {{ map.name }}
                    </div>

                    <!-- LEVEL -->
                    <div
                        class="text-xs"
                        :class="
                            user.player.current_level >= map.level_requirement
                                ? 'text-gray-400'
                                : 'text-red-500'
                        "
                    >
                        Level Required: {{ map.level_requirement }}
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
