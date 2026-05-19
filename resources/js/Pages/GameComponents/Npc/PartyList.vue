<script setup>
const props = defineProps({
    players: Object,
});
import { pushAlert } from "@/Stores/GlobalAlert";
import { usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
const page = usePage();
const playerId = page.props.auth.user?.player?.id;
const party = ref(null);
async function getMyParty() {
    let res = await axios.get("/get-party");
    party.value = res.data;
}

async function getPartyReward() {
    window.Echo.channel(`party.${party.value?.id}`).listen(
        ".party.reward",
        (e) => {
            const player = props.players.find((m) => m.id === e.player_id);

            player.current_experience += e.exp;
            player.current_gold += e.gold;

            pushAlert(
                `You received +${e.exp} EXP / +${e.gold} Gold from a party member.`,
                "success",
            );
        },
    );
}
onMounted(async () => {
    await getMyParty();
    getPartyReward();
});
</script>
<template>
    <div
        v-if="party"
        class="fixed left-1.5 top-[250px] -translate-y-1/2 w-48 bg-gray-900 border border-gray-700 rounded-xl p-3 text-white z-50"
    >
        <!-- HEADER -->
        <div
            class="text-xs text-gray-400 mb-3 flex justify-between items-center"
        >
            <span>Party</span>
            <span>{{ party?.members?.length || 0 }}/3</span>
        </div>

        <!-- MEMBERS LIST -->
        <div class="space-y-2">
            <div
                v-for="member in party?.members"
                :key="member.id"
                class="bg-gray-800 border border-gray-700 rounded-lg p-2 flex items-center justify-between"
            >
                <!-- LEFT: NAME -->
                <div class="flex flex-col">
                    <span class="text-sm font-semibold text-white">
                        {{ member.player.name }}
                    </span>

                    <span class="text-[10px] text-gray-400">
                        {{ member.player.class_type }}
                    </span>
                </div>

                <!-- RIGHT: STATS -->
                <div class="text-right text-[10px]">
                    <div class="text-green-400">
                        🟢 {{ member.player.is_online ? "online" : "offline" }}
                    </div>

                    <div class="text-red-400">
                        {{ member?.player.current_health }}/{{
                            member?.player.max_health
                        }}
                    </div>
                </div>
            </div>
        </div>

        <!-- EMPTY STATE -->
        <div
            v-if="!party?.members?.length"
            class="text-gray-500 text-xs text-center py-3"
        >
            No party members yet
        </div>
    </div>
</template>
