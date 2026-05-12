<script setup>
import { useForm } from "@inertiajs/vue3";
import { reactive } from "vue";

const form = useForm({
    msg_value: "",
});

const worldMessages = reactive([
    {
        name: "System",
        text: "Welcome to Elfaria MMORPG.",
    },
    {
        name: "KnightZero",
        text: "Anyone farming slimes?",
    },
    {
        name: "MageX",
        text: "Selling potions!",
    },
]);

function sendMessage() {
    if (!form.msg_value.trim()) return;
    form.post(route("npc.send-message"), {
        onSuccess: () => {
            form.msg_value = "";
        },
    });
}
</script>

<template>
    <div class="world-chat">
        <div class="chat-header">WORLD CHAT</div>

        <div class="chat-messages">
            <div
                v-for="(message, index) in worldMessages"
                :key="index"
                class="chat-message"
            >
                <span class="chat-name">{{ message.name }}:</span>
                <span class="chat-text">{{ message.text }}</span>
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

            <button class="chat-button" @click="sendMessage">Send</button>
        </div>
    </div>
</template>
<style scoped>
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

    font-size: 10px;
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
