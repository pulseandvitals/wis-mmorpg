<script setup>
import { useForm } from "@inertiajs/vue3";
import { nextTick, onMounted, ref, computed } from "vue";

const form = useForm({
    msg_value: "",
});

const messages = ref([]);
const isSending = ref(false);
const isLoading = ref(true);
const chatBox = ref(null);

/* =========================
CHAT CHANNEL STATE
========================= */
const activeChannel = ref("world"); // world | local | party

/* =========================
FILTERED MESSAGES
========================= */
const filteredMessages = computed(() => {
    return messages.value.filter((m) => m.channel === activeChannel.value);
});

/* =========================
SCROLL
========================= */
function scrollToBottom() {
    nextTick(() => {
        if (!chatBox.value) return;
        chatBox.value.scrollTop = chatBox.value.scrollHeight;
    });
}

/* =========================
MOUNT
========================= */
onMounted(async () => {
    getMessages();

    window.Echo.channel("world-chat").listen(".message.sent", (e) => {
        messages.value.push(e);

        if (messages.value.length > 50) {
            messages.value.shift();
        }

        scrollToBottom();
    });
});

/* =========================
LOAD MESSAGES
========================= */
async function getMessages() {
    const res = await axios.get("/streams/get-world-chat");
    messages.value = res.data;
    isLoading.value = false;
    scrollToBottom();
}

/* =========================
SEND MESSAGE
========================= */
async function sendMessage() {
    if (!form.msg_value.trim() || isSending.value) return;

    isSending.value = true;

    try {
        await axios.post("/send-message", {
            msg_value: form.msg_value,
            channel: activeChannel.value,
        });

        form.msg_value = "";
        scrollToBottom();
    } catch (err) {
        console.error(err);
    } finally {
        isSending.value = false;
    }
}
</script>

<template>
    <div class="world-chat">
        <!-- HEADER -->
        <div class="chat-header">
            <div class="flex justify-between items-center">
                <span>CHAT</span>

                <!-- CHANNEL SWITCH -->
                <div class="flex gap-2 text-[10px]">
                    <button
                        @click="activeChannel = 'world'"
                        :class="
                            activeChannel === 'world'
                                ? 'text-yellow-400'
                                : 'text-gray-400'
                        "
                    >
                        World
                    </button>

                    <button
                        @click="activeChannel = 'local'"
                        :class="
                            activeChannel === 'local'
                                ? 'text-green-400'
                                : 'text-gray-400'
                        "
                    >
                        Local
                    </button>

                    <button
                        @click="activeChannel = 'party'"
                        :class="
                            activeChannel === 'party'
                                ? 'text-blue-400'
                                : 'text-gray-400'
                        "
                    >
                        Party
                    </button>
                </div>
            </div>
        </div>

        <!-- LOADING -->
        <div v-if="isLoading" class="chat-loading">
            <div class="spinner"></div>
            <span>Connecting...</span>
        </div>

        <!-- MESSAGES -->
        <div v-else class="chat-messages" ref="chatBox">
            <div
                v-for="(message, index) in filteredMessages"
                :key="index"
                class="chat-message"
                :class="{ 'admin-message': message.is_admin }"
            >
                <!-- ADMIN MESSAGE -->
                <span v-if="message.is_admin" class="chat-badge">
                    📢 ANNOUNCEMENT:
                </span>

                <!-- NORMAL PLAYER MESSAGE -->
                <span v-else class="chat-name">
                    [{{ message.channel }}]
                    {{ message.player?.name ?? "Unknown" }}:
                </span>

                <span class="chat-text">
                    {{ message.message }}
                </span>
            </div>
        </div>

        <!-- INPUT -->
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
                <div v-if="isSending" class="spinner"></div>
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

.admin-message {
    background: rgba(255, 200, 0, 0.08);
    border-left: 3px solid #ffcc00;
    padding: 4px 6px;
    border-radius: 4px;
}

.chat-badge {
    color: #ffcc00;
    font-weight: bold;
    margin-right: 6px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.chat-text {
    color: #ffffff;
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
