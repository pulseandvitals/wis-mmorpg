<script setup>
import { computed, onMounted, ref } from "vue";
import { pushAlert } from "@/Stores/GlobalAlert";

const selectedPromo = ref(null);
const step = ref(1); // 1 = select promo, 2 = payment details
const loading = ref(false);
const referenceNumber = ref("");
const amountSent = ref("");
const bankAccount = ref("");

// Diamond Promos
const diamondPromos = [
    { id: 1, diamonds: 50, price: 49, bonus: 0, popular: false },
    { id: 2, diamonds: 120, price: 99, bonus: 10, popular: true },
    { id: 3, diamonds: 250, price: 199, bonus: 30, popular: false },
    { id: 4, diamonds: 500, price: 399, bonus: 60, popular: false },
    { id: 5, diamonds: 1200, price: 899, bonus: 200, popular: false },
    { id: 6, diamonds: 2500, price: 1799, bonus: 600, popular: false },
    { id: 7, diamonds: 5000, price: 3499, bonus: 1500, popular: false },
    { id: 8, diamonds: 10000, price: 6999, bonus: 3000, popular: false },
];

// Bank Account Info
const bankInfo = {
    bankName: "BDO Unibank",
    accountName: "Juan Dela Cruz",
    accountNumber: "001234567890",
    accountType: "Savings Account",
};

// QR Code (replace with your actual QR code image path)
const qrCodeImage = "/qr_code.png";

const totalDiamonds = computed(() => {
    if (!selectedPromo.value) return 0;
    return selectedPromo.value.diamonds + (selectedPromo.value.bonus || 0);
});

const formattedPrice = computed(() => {
    if (!selectedPromo.value) return "0";
    return selectedPromo.value.price.toLocaleString();
});

function selectPromo(promo) {
    selectedPromo.value = promo;
    step.value = 2;
}

function goBack() {
    step.value = 1;
    selectedPromo.value = null;
    referenceNumber.value = "";
    amountSent.value = "";
}

async function submitPayment() {
    if (!referenceNumber.value) {
        pushAlert("Please enter the reference number", "error");
        return;
    }

    if (!amountSent.value) {
        pushAlert("Please enter the amount you sent", "error");
        return;
    }

    try {
        loading.value = true;

        const res = await axios.post("/submit-topup", {
            promo: selectedPromo.value,
            reference_number: referenceNumber.value,
            amount_sent: amountSent.value,
        });

        pushAlert(
            res.data.message ||
                "Top-up request submitted! Please wait up to 30 minutes for processing.",
            "success",
        );

        // Reset and close modal after 3 seconds
        setTimeout(() => {
            step.value = 1;
            selectedPromo.value = null;
            referenceNumber.value = "";
            amountSent.value = "";
            loading.value = false;
            emit("close");
        }, 1000);
    } catch (error) {
        loading.value = false;
        pushAlert(
            error.response?.data?.message || "Failed to submit top-up request.",
            "error",
        );
        console.error("Top-up Error:", error);
    }
}

const emit = defineEmits(["close"]);
</script>

<template>
    <div class="npc-modal">
        <div class="npc-modal-content p-6">
            <div class="flex justify-between items-center mb-6">
                <h1
                    class="text-3xl text-white font-bold tracking-wide flex items-center gap-2"
                >
                    💎 Diamond Top-Up
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
                    <!-- STEP 1: SELECT PROMO -->
                    <div v-if="step === 1">
                        <!-- HEADER -->
                        <div
                            class="flex items-center justify-between border-b border-white/10 pb-5 mb-6"
                        >
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-16 h-16 rounded-2xl bg-yellow-500/10 border border-yellow-400/30 flex items-center justify-center text-4xl shadow-lg shadow-yellow-500/10"
                                >
                                    💎
                                </div>

                                <div>
                                    <p class="text-sm text-gray-400 mt-1">
                                        Choose your diamond package
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- PROMO GRID -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div
                                v-for="promo in diamondPromos"
                                :key="promo.id"
                                @click="selectPromo(promo)"
                                class="relative rounded-xl border transition cursor-pointer p-4 text-center"
                                :class="
                                    promo.popular
                                        ? 'border-yellow-500 bg-gradient-to-br from-yellow-500/20 to-amber-500/10 hover:from-yellow-500/30 hover:to-amber-500/20'
                                        : 'border-white/10 bg-black/20 hover:bg-white/5'
                                "
                            >
                                <div
                                    v-if="promo.popular"
                                    class="absolute -top-3 left-1/2 transform -translate-x-1/2"
                                >
                                    <span
                                        class="bg-yellow-500 text-black text-xs font-bold px-3 py-1 rounded-full"
                                    >
                                        POPULAR
                                    </span>
                                </div>

                                <div class="text-3xl mb-2">💎</div>
                                <div class="text-2xl font-bold text-white">
                                    {{ promo.diamonds }}
                                </div>
                                <div
                                    v-if="promo.bonus > 0"
                                    class="text-xs text-green-400"
                                >
                                    +{{ promo.bonus }} Bonus
                                </div>
                                <div class="text-sm text-gray-400 mt-2">
                                    PHP {{ promo.price.toLocaleString() }}
                                </div>
                                <div class="text-xs text-yellow-400 mt-1">
                                    Total: {{ promo.diamonds + promo.bonus }} 💎
                                </div>
                            </div>
                        </div>

                        <!-- NOTE -->
                        <div class="mt-6 pt-4 border-t border-white/10">
                            <p class="text-xs text-gray-400 text-center">
                                💡 All transactions are manually processed.
                                Please wait up to 30 minutes after payment.
                            </p>
                        </div>
                    </div>

                    <!-- STEP 2: PAYMENT DETAILS -->
                    <div v-if="step === 2 && selectedPromo">
                        <!-- HEADER -->
                        <div
                            class="flex items-center justify-between border-b border-white/10 pb-5 mb-6"
                        >
                            <button
                                @click="goBack"
                                class="px-3 py-1 text-sm bg-gray-600/50 hover:bg-gray-600 rounded-lg text-white transition"
                            >
                                ← Back
                            </button>
                        </div>

                        <!-- ORDER SUMMARY -->
                        <div
                            class="bg-gradient-to-r from-purple-500/10 to-pink-500/10 rounded-xl p-4 mb-6 border border-purple-500/20"
                        >
                            <h3 class="text-white font-bold mb-2">
                                Order Summary
                            </h3>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Package:</span>
                                <span class="text-white"
                                    >{{ selectedPromo.diamonds }} Diamonds</span
                                >
                            </div>
                            <div
                                v-if="selectedPromo.bonus > 0"
                                class="flex justify-between text-sm"
                            >
                                <span class="text-gray-400">Bonus:</span>
                                <span class="text-green-400"
                                    >+{{ selectedPromo.bonus }} Diamonds</span
                                >
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400"
                                    >Total Diamonds:</span
                                >
                                <span class="text-yellow-400 font-bold"
                                    >{{ totalDiamonds }} 💎</span
                                >
                            </div>
                            <div
                                class="flex justify-between text-sm mt-2 pt-2 border-t border-white/10"
                            >
                                <span class="text-gray-400"
                                    >Amount to Pay:</span
                                >
                                <span class="text-green-400 font-bold"
                                    >PHP {{ formattedPrice }}</span
                                >
                            </div>
                        </div>

                        <!-- BANK DETAILS -->
                        <!-- <div
                            class="bg-black/30 rounded-xl p-4 mb-6 border border-white/10"
                        >
                            <h3
                                class="text-white font-bold mb-3 flex items-center gap-2"
                            >
                                🏦 Bank Transfer Details
                            </h3>

                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Bank:</span>
                                    <span class="text-white">{{
                                        bankInfo.bankName
                                    }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400"
                                        >Account Name:</span
                                    >
                                    <span class="text-white">{{
                                        bankInfo.accountName
                                    }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400"
                                        >Account Number:</span
                                    >
                                    <div class="flex items-center gap-2">
                                        <span class="text-white font-mono">{{
                                            bankInfo.accountNumber
                                        }}</span>
                                        <button
                                            @click="
                                                copyToClipboard(
                                                    bankInfo.accountNumber,
                                                )
                                            "
                                            class="text-xs bg-gray-600/50 hover:bg-gray-600 px-2 py-1 rounded"
                                        >
                                            Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400"
                                        >Account Type:</span
                                    >
                                    <span class="text-white">{{
                                        bankInfo.accountType
                                    }}</span>
                                </div>
                            </div>
                        </div> -->

                        <!-- QR + PAYMENT FORM (2 COLUMNS) -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <!-- QR CODE -->
                            <div class="text-center">
                                <div
                                    class="inline-block p-3 bg-white rounded-xl"
                                >
                                    <img
                                        :src="qrCodeImage"
                                        alt="QR Code"
                                        class="w-[200px] h-[200px] object-contain"
                                        @error="
                                            (e) =>
                                                (e.target.style.display =
                                                    'none')
                                        "
                                    />
                                </div>

                                <p class="text-xs text-gray-500 mt-2">
                                    Scan with your banking app
                                </p>
                            </div>

                            <!-- PAYMENT FORM -->
                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="text-sm text-gray-400 block mb-2"
                                    >
                                        Amount You Sent (PHP)
                                    </label>

                                    <input
                                        v-model="amountSent"
                                        type="number"
                                        :placeholder="`Enter exact amount: ${selectedPromo.price}`"
                                        class="w-full px-4 py-2 rounded-lg bg-black/50 border border-white/10 text-white focus:border-purple-500 focus:outline-none"
                                    />

                                    <p class="text-xs text-gray-500 mt-1">
                                        Please send the exact amount for faster
                                        processing
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="text-sm text-gray-400 block mb-2"
                                    >
                                        Reference Number
                                    </label>

                                    <input
                                        v-model="referenceNumber"
                                        type="text"
                                        placeholder="Enter transaction reference number"
                                        class="w-full px-4 py-2 rounded-lg bg-black/50 border border-white/10 text-white focus:border-purple-500 focus:outline-none"
                                    />

                                    <p class="text-xs text-gray-500 mt-1">
                                        Found on your bank receipt or
                                        transaction history
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- INFO MESSAGE -->
                        <div
                            class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-3 mb-6"
                        >
                            <p class="text-xs text-blue-300 text-center">
                                ⏰ Please wait up to 30 minutes for manual
                                verification.<br />
                                Your diamonds will be added to your account
                                after confirmation.
                            </p>
                        </div>

                        <!-- SUBMIT BUTTON -->
                        <button
                            @click="submitPayment"
                            :disabled="loading"
                            class="w-full py-3 rounded-lg font-bold transition bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white"
                        >
                            {{ loading ? "Submitting..." : "Submit Payment" }}
                        </button>
                    </div>

                    <!-- FOOTER -->
                    <div
                        class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between"
                    >
                        <p class="text-xs text-gray-500">
                            Secure payment powered by manual verification
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
    max-width: 800px;
    min-height: 400px;
    max-height: 90vh;
    overflow-y: auto;

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

    background: rgba(0, 0, 0, 0.75);

    display: flex;
    align-items: center;
    justify-content: center;

    z-index: 999999999;
}

/* Scrollbar */
.npc-modal-content::-webkit-scrollbar {
    width: 8px;
}

.npc-modal-content::-webkit-scrollbar-track {
    background: #1f2937;
    border-radius: 4px;
}

.npc-modal-content::-webkit-scrollbar-thumb {
    background: #8b5cf6;
    border-radius: 4px;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
