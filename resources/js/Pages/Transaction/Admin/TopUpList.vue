<script setup>
import { ref, computed, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import { pushAlert } from "@/Stores/GlobalAlert";

const props = defineProps({
    isAdmin: {
        type: Boolean,
    },
});

const emit = defineEmits(["close"]);

const processingId = ref(null);
const topups = ref([]);
const loading = ref(false);
const statusFilter = ref("pending"); // default view
const closeModal = () => {
    emit("close");
};
const filteredTopups = computed(() => {
    return topups.value.filter((t) => t.status === statusFilter.value);
});
async function getAllTopUps() {
    try {
        let res = await axios.get("/get-top-ups");
        topups.value = res.data.topups;
    } catch (e) {
        pushAlert(e.data.error || "Theres something wrong..");
    }
}
async function approveTopUp(id) {
    processingId.value = id;
    loading.value = true;
    try {
        let res = await axios.post("/approve-top-up", {
            id: processingId.value,
        });
        pushAlert(res.data.message || "Successfully approved!");
        loading.value = false;
        await getAllTopUps();
    } catch (e) {
        pushAlert(res.data.error || "Something went wrong!");
        loading.value = false;
    }
}

onMounted(() => {
    getAllTopUps();
});
</script>

<template>
    <div
        v-if="props.isAdmin"
        class="fixed inset-0 bg-black/70 flex items-center justify-center z-50"
    >
        <div
            class="w-[600px] bg-gray-900 border border-gray-700 rounded-xl text-white"
        >
            <!-- HEADER -->
            <div
                class="flex justify-between items-center p-4 border-b border-gray-700"
            >
                <h2 class="text-lg font-bold">Pending Top-Ups</h2>
                <button
                    @click="closeModal"
                    class="text-gray-400 hover:text-white"
                >
                    ✕
                </button>
            </div>

            <div class="flex gap-2 p-4 border-b border-gray-700">
                <button
                    @click="statusFilter = 'pending'"
                    class="px-3 py-1 text-sm rounded"
                    :class="
                        statusFilter === 'pending'
                            ? 'bg-yellow-600 text-white'
                            : 'bg-gray-700 text-gray-300'
                    "
                >
                    Pending
                </button>

                <button
                    @click="statusFilter = 'completed'"
                    class="px-3 py-1 text-sm rounded"
                    :class="
                        statusFilter === 'completed'
                            ? 'bg-green-600 text-white'
                            : 'bg-gray-700 text-gray-300'
                    "
                >
                    Completed
                </button>
            </div>

            <!-- BODY -->
            <div class="p-4 max-h-[400px] overflow-y-auto">
                <div v-if="topups.length === 0" class="text-gray-400 text-sm">
                    No pending top-ups.
                </div>

                <div
                    v-for="topup in filteredTopups"
                    :key="topup.id"
                    class="flex items-center justify-between bg-gray-800 p-3 rounded-lg mb-3"
                >
                    <div>
                        <p class="font-semibold">
                            {{ topup.user?.name }} ({{ topup.user?.email }})
                        </p>
                        <p class="text-xs text-gray-400">
                            Amount: ₱{{ topup.cash_amount }}
                        </p>
                        <p class="text-xs text-gray-500">
                            Ref: {{ topup.reference_number }}
                        </p>
                    </div>

                    <span
                        class="text-xs px-2 py-1 rounded"
                        :class="
                            topup.status === 'pending'
                                ? 'bg-yellow-600/30 text-yellow-300'
                                : 'bg-green-600/30 text-green-300'
                        "
                    >
                        {{ topup.status }}
                    </span>

                    <button
                        v-if="topup?.status === 'pending'"
                        @click="approveTopUp(topup.id)"
                        class="px-3 py-1 text-sm rounded bg-green-600 hover:bg-green-500 disabled:opacity-50"
                        :disabled="loading"
                    >
                        {{ loading ? "Processing..." : "Approve" }}
                    </button>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="p-3 border-t border-gray-700 text-right">
                <button
                    @click="closeModal"
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>
