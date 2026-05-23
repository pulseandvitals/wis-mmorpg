<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import PlayerSkillModal from "./PlayerSkillModal.vue";
import PlayerInventoryModal from "./PlayerInventoryModal.vue";
import Ranking from "./Npc/Ranking.vue";
import PartyRoom from "./Npc/PartyRoom.vue";
import { pushAlert } from "@/Stores/GlobalAlert";
import Topup from "../Transaction/Topup.vue";
import TalentTree from "./TalentTree.vue";
import TopUpList from "../Transaction/Admin/TopUpList.vue";
const props = defineProps({
    classSkills: Object,
    all_maps: Object,
    player: Object,
});

const form = useForm({});
const isSkillOpen = ref(false);
const isInventoryOpen = ref(false);
const isRankingOpen = ref(false);
const isPartyOpen = ref(false);
const isTopUpOpen = ref(false);
const isTalentTreeOpen = ref(false);
const isAdminOpen = ref(false);
const page = usePage();
const isAdmin = page.props.auth?.user?.role === "admin";
const emit = defineEmits(["updatePlayer", "updateParty"]);
const map = props.all_maps.find((map) => map.name === "Wisteria Town");

const menuItems = computed(() => {
    return [
        { id: 1, label: "Inventory", icon: "🎒" },
        { id: 2, label: "Party", icon: "👥" },
        { id: 3, label: "Skills", icon: "📖" },
        { id: 4, label: "Talent Tree", icon: "🌀" },
        { id: 5, label: "Ranking", icon: "🏆" },
        { id: 6, label: "Discord", icon: "💬" },
        { id: 7, label: "Settings", icon: "⚙️" },
        { id: 8, label: "Top Up", icon: "💎" },

        ...(isAdmin ? [{ id: 9, label: "Admin", icon: "🛡️" }] : []),

        { id: 10, label: "Back to Town", icon: "🏡" },
    ];
});

const handleMenuClick = (item) => {
    if (item.id === 1) {
        isInventoryOpen.value = true;
    }
    if (item.id === 2) {
        isPartyOpen.value = true;
    }
    if (item.id === 3) {
        isSkillOpen.value = true;
    }
    if (item.id === 4) {
        isTalentTreeOpen.value = true;
    }
    if (item.id === 5) {
        isRankingOpen.value = true;
    }
    if (item.id === 7) {
        window.open("https://discord.com", "_blank");
    }
    if (item.id === 8) {
        isTopUpOpen.value = true;
    }
    if (item.id === 9) {
        isAdminOpen.value = true;
    }
    if (item.id === 10) {
        form.get(route("world.map", map.map_id), {
            onFinish: () => pushAlert(map.name, "success"),
        });
    }
};
</script>
<template>
    <div class="hud-menu">
        <button
            v-for="item in menuItems"
            :key="item.id"
            class="hud-btn"
            @click="handleMenuClick(item)"
        >
            <span class="hud-icon">
                {{ item.icon }}
            </span>

            <span class="hud-label">
                {{ item.label }}
            </span>
        </button>
    </div>
    <PlayerInventoryModal
        v-if="isInventoryOpen"
        :player="player"
        @updatePlayer="emit('updatePlayer', $event)"
        @close="isInventoryOpen = false"
    />
    <PartyRoom
        v-if="isPartyOpen"
        @updateParty="emit('updateParty', $event)"
        @close="isPartyOpen = false"
    />
    <PlayerSkillModal
        :classSkills="classSkills"
        v-if="isSkillOpen"
        @close="isSkillOpen = false"
    />
    <Ranking v-if="isRankingOpen" @close="isRankingOpen = false" />
    <Topup v-if="isTopUpOpen" @close="isTopUpOpen = false" />
    <TopUpList
        v-if="isAdminOpen"
        :isAdmin="isAdmin"
        @close="isAdminOpen = false"
    />
    <TalentTree
        :player="player"
        v-if="isTalentTreeOpen"
        @close="isTalentTreeOpen = false"
    />
</template>
<style scoped>
.hud-menu {
    position: absolute;
    top: 12px;
    right: 12px;

    display: flex;
    flex-direction: column;
    gap: 6px;

    z-index: 999;
}

.hud-btn {
    display: flex;
    align-items: center;
    gap: 6px;

    padding: 6px 10px;

    background: rgba(0, 0, 0, 0.55);
    backdrop-filter: blur(6px);

    border: 1px solid rgba(251, 191, 36, 0.4);
    border-radius: 6px;

    color: #fbbf24;

    font-size: 15px;
    font-family: "Nova Square", sans-serif;

    cursor: pointer;

    transition: all 0.15s ease;
}

.hud-btn:hover {
    background: rgba(251, 191, 36, 0.15);
    transform: translateX(-2px);
}

.hud-icon {
    font-size: 15px;
}

.hud-label {
    white-space: nowrap;
}
</style>
