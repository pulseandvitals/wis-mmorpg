<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, onMounted, ref } from "vue";

const weapons = ref([]);
const selectedWeapon = ref(null);
async function getWeapons() {
    let resp = await axios.get("/get-weapons");
    weapons.value = resp.data.weapons;
}

const weaponTabs = ["Sword", "Spear", "Bow", "Staff", "Dagger"];

const selectedWeaponTab = ref("Sword");

function getWeaponType(name) {
    const parts = name.split(" ");
    return parts[parts.length - 1]; // last word = type
}

const filteredWeapons = computed(() => {
    return weapons.value.filter((w) => {
        return getWeaponType(w.name) === selectedWeaponTab.value;
    });
});
onMounted(() => {
    getWeapons();
});
</script>
<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl text-white font-bold tracking-wide">
                    Weapon House
                </h1>

                <button
                    @click="$emit('close')"
                    class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-white transition"
                >
                    Close
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div
                    class="col-span-2 rounded-2xl border border-gray-500 p-6 shadow-2xl shadow-black/40"
                >
                    <!-- HEADER -->
                    <div
                        class="flex items-center justify-between border-b border-white/10 pb-5 mb-6"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                class="w-16 h-16 rounded-2xl bg-red-500/10 border border-red-400/30 flex items-center justify-center text-4xl shadow-lg shadow-red-500/10"
                            >
                                ⚔️
                            </div>

                            <div>
                                <p class="text-sm text-gray-400 mt-1">
                                    Choose your weapon path
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- MENU -->
                    <div class="flex gap-2 mb-6 flex-wrap">
                        <button
                            v-for="tab in weaponTabs"
                            :key="tab"
                            @click="selectedWeaponTab = tab"
                            class="px-4 py-2 rounded-lg text-sm border transition"
                            :class="
                                selectedWeaponTab === tab
                                    ? 'bg-red-500/20 border-red-400 text-white'
                                    : 'bg-black/20 border-white/10 text-gray-300 hover:bg-white/5'
                            "
                        >
                            {{ tab }}
                        </button>
                    </div>

                    <!-- WEAPON LIST -->
                    <div class="space-y-3">
                        <div
                            v-for="(weapon, index) in filteredWeapons"
                            :key="weapon.id"
                            tabindex="0"
                            class="flex items-center justify-between rounded-xl border border-white/10 bg-black/20 hover:bg-red-500/5 transition px-5 py-4 cursor-pointer outline-none focus:ring-2 focus:ring-red-400/40"
                            @focus="selectedWeapon = weapon"
                            @blur="selectedWeapon = null"
                        >
                            <!-- LEFT -->
                            <div class="flex items-center gap-4">
                                <!-- ICON -->
                                <img
                                    :src="`/weapons/${weapon.name}.png`"
                                    class="w-10 h-10 object-contain"
                                />

                                <!-- NAME -->
                                <div>
                                    <p class="text-white font-bold">
                                        {{ weapon.name }}
                                    </p>
                                </div>
                            </div>

                            <!-- RIGHT -->
                            <div class="text-right">
                                <button
                                    class="px-4 py-2 text-xs font-bold rounded-lg border border-green-400/30 bg-green-500/10 text-green-300 hover:bg-green-500/20 hover:border-green-400/60 transition"
                                    @click="craftWeapon(weapon)"
                                >
                                    Craft
                                </button>
                            </div>
                        </div>
                    </div>

                    <transition name="fade">
                        <div
                            v-if="selectedWeapon"
                            class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none"
                        >
                            <div
                                class="w-[340px] rounded-2xl border border-white/10 bg-black/85 p-5 shadow-2xl"
                            >
                                <!-- HEADER -->
                                <div
                                    class="flex items-center gap-3 border-b border-white/10 pb-4 mb-4"
                                >
                                    <img
                                        :src="`/weapons/${selectedWeapon.name}.png`"
                                        class="w-12 h-12 object-contain"
                                    />

                                    <div>
                                        <p class="text-white font-bold">
                                            {{ selectedWeapon.name }}
                                        </p>
                                        <p class="text-white text-sm">
                                            Lvl.{{
                                                selectedWeapon.requirement_level
                                            }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            Weapon Details
                                        </p>
                                    </div>
                                </div>
                                <!-- STATS -->
                                <div class="space-y-2 text-sm text-gray-300">
                                    <p
                                        v-if="
                                            selectedWeapon.basic_stats?.attack
                                        "
                                    >
                                        ⚔ Attack:
                                        {{ selectedWeapon.basic_stats.attack }}
                                    </p>

                                    <p
                                        v-if="
                                            selectedWeapon.basic_stats
                                                ?.crit_rate
                                        "
                                    >
                                        💥 Crit Rate:
                                        {{
                                            selectedWeapon.basic_stats
                                                .crit_rate
                                        }}%
                                    </p>

                                    <p
                                        v-if="
                                            selectedWeapon.basic_stats
                                                ?.attack_speed
                                        "
                                    >
                                        ⚡ Attack Speed:
                                        {{
                                            selectedWeapon.basic_stats
                                                .attack_speed
                                        }}
                                    </p>

                                    <p
                                        v-if="
                                            selectedWeapon.basic_stats?.defense
                                        "
                                    >
                                        🛡 Defense:
                                        {{ selectedWeapon.basic_stats.defense }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </transition>
                    <!-- FOOTER -->
                    <div
                        class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between"
                    >
                        <p class="text-xs text-gray-500">
                            Forge your destiny in the Weapon House
                        </p>
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
