<script setup>
import { useForm } from "@inertiajs/vue3";
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from "vue";
const form = useForm({
    msg_value: "",
});
const messages = ref([]);
const isSending = ref(false);
const isLoading = ref(true);
const chatBox = ref(null);
let eventSource = null;
onMounted(() => {
    eventSource = new EventSource("/streams/get-world-chat");

    eventSource.onmessage = (event) => {
        messages.value = JSON.parse(event.data);
        isLoading.value = false;
    };
    eventSource.onerror = (err) => {
        console.log("SSE error:", err);
    };
});
onBeforeUnmount(() => {
    if (eventSource) {
        eventSource.close();
    }
});
async function sendMessage() {
    if (!form.msg_value.trim() || isSending.value) return;

    isSending.value = true;

    try {
        await axios.post("/send-message", {
            msg_value: form.msg_value,
        });

        form.msg_value = ""; // clear input after send
    } catch (err) {
        console.error(err);
    } finally {
        isSending.value = false;
    }
}

watch(messages, async () => {
    await nextTick();

    if (chatBox.value) {
        chatBox.value.scrollTop = chatBox.value.scrollHeight;
    }
});
</script>

<template>
    <div class="world-chat">
        <div class="chat-header">WORLD CHAT</div>
        <div v-if="isLoading" class="chat-loading">
            <div class="spinner"></div>
            <span>Connecting to world chat...</span>
        </div>
        <div v-else class="chat-messages" ref="chatBox">
            <div
                v-for="(message, index) in messages"
                :key="index"
                class="chat-message"
            >
                <span class="chat-name">{{ message.player.name }}: </span>
                <span class="chat-text">{{ message.message }}</span>
            </div>
        </div>

        <div class="chat-input-wrapper">
            <input
                v-model="form.msg_value"
                type="text"
                class="chat-input"
                placeholder="Type message..."
                @keydown.enter="sendMessage"
            />

            <button
                @click="sendMessage"
                :disabled="isSending"
                class="chat-button"
            >
                <div v-if="isSending" class="chat-loading">
                    <div class="spinner"></div>
                </div>
                <div v-else>Send</div>
            </button>
        </div>
    </div>
</template>
<style scoped>
.chat-loading {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px;
    color: #aaa;
    font-size: 12px;
}

.spinner {
    width: 14px;
    height: 14px;
    border: 2px solid #ccc;
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
.spinner {
    width: 14px;
    height: 14px;
    border: 2px solid #fff;
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
.world-chat {
    position: absolute;
    left: 5px;
    bottom: 5px;

    max-width: 90vw;
    width: 420px;
    height: 260px;

    background: rgba(0, 0, 0, 0.75);
    border: 3px solid #374151;

    display: flex;
    flex-direction: column;

    overflow: hidden;
    z-index: 999;
}

.chat-header {
    padding: 10px;

    background: rgba(255, 255, 255, 0.08);

    color: #facc15;

    font-size: 10px;

    border-bottom: 2px solid rgba(255, 255, 255, 0.08);
}

.chat-messages {
    flex: 1;

    padding: 5px;

    overflow-y: auto;

    display: flex;
    flex-direction: column;
    gap: 5px;

    color: white;

    font-size: 12px;
}

.chat-message {
    line-height: 1.6;
    word-break: break-word;
}

.chat-name {
    color: #60a5fa;
}

.chat-text {
    color: white;
}

.chat-input-wrapper {
    display: flex;

    border-top: 2px solid rgba(255, 255, 255, 0.08);
}

.chat-input {
    flex: 1;

    background: rgba(0, 0, 0, 0.5);

    border: none;
    outline: none;

    padding: 12px;

    color: white;

    font-family: inherit;
    font-size: 15px;
}

.chat-button {
    width: 90px;

    border: none;

    background: #2563eb;

    color: white;

    cursor: pointer;

    font-family: inherit;
    font-size: 10px;
}

.chat-button:hover {
    background: #3b82f6;
}
</style>
