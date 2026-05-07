<script setup>
import { ref, nextTick } from "vue";
import { router } from "@inertiajs/vue3";

const message = ref("");
const chatBox = ref(null);

// props from backend
const props = defineProps({
    messages: Array,
});

const sendMessage = () => {
    if (!message.value.trim()) return;

    router.post(
        "/world-chat",
        {
            message: message.value,
        },
        {
            preserveScroll: true,
            onSuccess: async () => {
                message.value = "";
                await nextTick();
                scrollToBottom();
            },
        },
    );
};

const scrollToBottom = () => {
    if (chatBox.value) {
        chatBox.value.scrollTop = chatBox.value.scrollHeight;
    }
};
</script>

<template>
    <div
        class="w-full max-w-md bg-black/60 border border-white/10 rounded-2xl overflow-hidden flex flex-col"
    >
        <!-- Header -->
        <div class="p-3 bg-black/80 border-b border-white/10">
            <h2 class="text-yellow-300 font-bold tracking-widest">
                🌍 WORLD CHAT
            </h2>
        </div>

        <!-- Messages -->
        <div ref="chatBox" class="h-80 overflow-y-auto p-3 space-y-2 text-sm">
            <div
                v-for="(msg, index) in messages"
                :key="index"
                class="bg-white/5 p-2 rounded-lg"
            >
                <span class="text-green-400 font-bold">
                    {{ msg.user }}
                </span>
                <span class="text-gray-300">:</span>
                <span class="text-white ml-1">
                    {{ msg.message }}
                </span>
            </div>
        </div>

        <!-- Input -->
        <form
            @submit.prevent="sendMessage"
            class="flex border-t border-white/10"
        >
            <input
                v-model="message"
                type="text"
                placeholder="Type message..."
                class="flex-1 bg-black/70 text-white px-3 py-2 outline-none"
            />

            <button
                type="submit"
                class="px-4 bg-yellow-400 text-black font-bold hover:bg-yellow-300 transition"
            >
                Send
            </button>
        </form>
    </div>
</template>
