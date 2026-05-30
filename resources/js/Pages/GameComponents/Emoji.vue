<script setup>
import { ref } from "vue";

const isOpen = ref(false);

const emojis = [
    {
        id: 1,
        name: "confused",
        image: "/emojis/Confused.png",
    },
    {
        id: 2,
        name: "crying",
        image: "/emojis/Crying.png",
    },
    {
        id: 3,
        name: "curious",
        image: "/emojis/Curious.png",
    },
    {
        id: 4,
        name: "disgusted",
        image: "/emojis/Disgusted.png",
    },
    {
        id: 5,
        name: "drunk",
        image: "/emojis/Drunk.png",
    },
    {
        id: 6,
        name: "flirty",
        image: "/emojis/Flirty.png",
    },
    {
        id: 7,
        name: "happy",
        image: "/emojis/Happy.png",
    },
    {
        id: 8,
        name: "incredulous",
        image: "/emojis/Incredulous.png",
    },
    {
        id: 9,
        name: "irritated",
        image: "/emojis/Irritated.png",
    },
    {
        id: 10,
        name: "laughing",
        image: "/emojis/Laughing.png",
    },
    {
        id: 11,
        name: "nervous",
        image: "/emojis/Nervous.png",
    },
    {
        id: 12,
        name: "pain",
        image: "/emojis/Pain.png",
    },
    {
        id: 13,
        name: "pleased",
        image: "/emojis/Pleased.png",
    },
    {
        id: 14,
        name: "pouty",
        image: "/emojis/Pouty.png",
    },
    {
        id: 15,
        name: "rage",
        image: "/emojis/Rage.png",
    },
    {
        id: 16,
        name: "rofl",
        image: "/emojis/Rofl.png",
    },
    {
        id: 17,
        name: "sad",
        image: "/emojis/Sad.png",
    },
    {
        id: 18,
        name: "scared",
        image: "/emojis/Scared.png",
    },
    {
        id: 19,
        name: "serious",
        image: "/emojis/Serious.png",
    },
    {
        id: 20,
        name: "sick",
        image: "/emojis/Sick.png",
    },
    {
        id: 21,
        name: "silly",
        image: "/emojis/Silly.png",
    },
    {
        id: 22,
        name: "smile",
        image: "/emojis/Smile.png",
    },
    {
        id: 23,
        name: "smirk",
        image: "/emojis/Smirk.png",
    },
    {
        id: 24,
        name: "snooty",
        image: "/emojis/Snooty.png",
    },
    {
        id: 25,
        name: "surprised",
        image: "/emojis/Surprised.png",
    },
    {
        id: 26,
        name: "tired",
        image: "/emojis/Tired.png",
    },
    {
        id: 27,
        name: "wtf",
        image: "/emojis/Wtf.png",
    },
    {
        id: 28,
        name: "angry",
        image: "/emojis/Angry.png",
    },
    {
        id: 29,
        name: "bored",
        image: "/emojis/Bored.png",
    },
    {
        id: 30,
        name: "calm",
        image: "/emojis/Calm.png",
    },
    {
        id: 31,
        name: "cold",
        image: "/emojis/Cold.png",
    },
    {
        id: 32,
        name: "concerned",
        image: "/emojis/Concerned.png",
    },
    {
        id: 32,
        name: "confident",
        image: "/emojis/Confident.png",
    },
];

const emit = defineEmits(["select"]);

async function selectEmoji(emoji) {
    try {
        let res = await axios.post("/send-emoji", {
            emoji: emoji,
        });

        emit("select", res.data.emoji);
        isOpen.value = false;
    } catch (e) {
        isOpen.value = false;
    }
}
</script>

<template>
    <div class="emoji-wrapper">
        <!-- Open Button -->
        <button @click="isOpen = !isOpen" class="emoji-button">Emoji</button>

        <!-- Popup -->
        <Transition name="emoji">
            <div v-if="isOpen" class="emoji-popup">
                <div class="emoji-header">
                    <span>Emotes</span>

                    <button @click="isOpen = false" class="close-btn">✕</button>
                </div>

                <div class="emoji-grid">
                    <button
                        v-for="emoji in emojis"
                        :key="emoji.id"
                        @click="selectEmoji(emoji)"
                        class="emoji-item"
                    >
                        <img
                            :src="emoji.image"
                            :alt="emoji.name"
                            class="emoji-image"
                        />
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>
<style scoped>
.emoji-button {
    position: absolute;
    left: 430px; /* consider switching to right-based or flex layout later */
    bottom: 5px;

    padding: 6px 12px; /* fixed */
    background: linear-gradient(145deg, #ef4444, #b91c1c);
    color: white;

    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;

    font-size: 13px;
    font-weight: 600;

    cursor: pointer;
    user-select: none;

    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);

    transition:
        transform 0.15s ease,
        box-shadow 0.15s ease,
        background 0.2s ease;

    z-index: 999;
}

.emoji-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.35);
    background: linear-gradient(145deg, #f87171, #dc2626);
}

.emoji-button:active {
    transform: translateY(0px) scale(0.98);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}
.emoji-popup {
    position: absolute;
    bottom: 55px;
    left: 50%;
    transform: translateX(-50%);

    width: 320px;

    background: linear-gradient(
        to bottom,
        rgba(31, 41, 55, 0.98),
        rgba(17, 24, 39, 0.98)
    );

    border: 1px solid #4b5563;
    border-radius: 14px;

    overflow: hidden;

    box-shadow:
        0 0 25px rgba(0, 0, 0, 0.8),
        0 0 10px rgba(251, 191, 36, 0.15);
    z-index: 9999;
}

.emoji-header {
    display: flex;
    justify-content: space-between;
    align-items: center;

    padding: 10px 12px;

    border-bottom: 1px solid #374151;

    color: #fbbf24;
    font-size: 12px;
    font-weight: bold;
}

.emoji-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 8px;

    padding: 12px;
}

.emoji-item {
    width: 52px;
    height: 52px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: rgba(55, 65, 81, 0.5);
    border: 1px solid #4b5563;
    border-radius: 10px;

    transition: all 0.15s ease;
}

.emoji-item:hover {
    transform: scale(1.1);
    border-color: #fbbf24;
    background: rgba(75, 85, 99, 0.8);
}

.emoji-image {
    width: 38px;
    height: 38px;
    object-fit: contain;
    image-rendering: pixelated; /* perfect for MMORPG assets */
}
</style>
