<script setup>
import { ref, computed } from "vue";

const showTutorial = ref(true);

const currentStep = ref(0);

const tutorials = [
    {
        title: "Go to the Monster Map",
        description:
            "Open your map and travel to the hunting area to begin your adventure.",
        image: "/tutorials/Step1.png",
    },
    {
        title: "Attack Monsters",
        description:
            "Click monsters to attack them and gain EXP, gold, and item drops.",
        image: "/tutorials/Step2.png",
    },
    {
        title: "Low HP & Mana",
        description:
            "If your HP or Mana becomes low, return to town before getting defeated.",
        image: "/tutorials/Step3.png",
    },
    {
        title: "Heal in Town",
        description:
            "Talk to the Healer NPC to fully restore your HP and Mana.",
        image: "/tutorials/Step4.png",
    },
    {
        title: "Continue Hunting",
        description:
            "After healing, return to the hunting map and continue leveling.",
        image: "/tutorials/Step5.png",
    },
    {
        title: "Buy EXP Potion",
        description:
            "Want faster EXP? Visit the Potion Shop in town and buy EXP Potions.",
        image: "/tutorials/Step6.png",
    },
    {
        title: "Find a Party",
        description: "Join a party for faster EXP and easier monster farming.",
        image: "/tutorials/Step7.png",
    },
    {
        title: "Stay on Same Map",
        description:
            "Party members must stay on the same map to receive EXP sharing.",
        image: "/tutorials/Step8.png",
    },
    {
        title: "Farm Materials",
        description:
            "Collect monster drops and crafting materials while farming.",
        image: "/tutorials/Step9.png",
    },
    {
        title: "Craft Equipment",
        description:
            "Use materials at the Blacksmith to craft stronger weapons and armors.",
        image: "/tutorials/Step10.png",
    },
];

const tutorial = computed(() => tutorials[currentStep.value]);

const nextStep = () => {
    if (currentStep.value < tutorials.length - 1) {
        currentStep.value++;
    } else {
        showTutorial.value = false;
    }
};

const prevStep = () => {
    if (currentStep.value > 0) {
        currentStep.value--;
    }
};
</script>

<template>
    <div v-if="showTutorial" class="tutorial-overlay">
        <div class="tutorial-modal">
            <img
                :src="tutorial.image"
                class="tutorial-image"
                alt="Tutorial Image"
            />

            <div class="tutorial-content">
                <div class="tutorial-step">
                    Step {{ currentStep + 1 }} / {{ tutorials.length }}
                </div>

                <h2>{{ tutorial.title }}</h2>

                <p>
                    {{ tutorial.description }}
                </p>
            </div>

            <div class="tutorial-buttons">
                <button
                    @click="prevStep"
                    :disabled="currentStep === 0"
                    class="btn prev"
                >
                    Previous
                </button>

                <button @click="nextStep" class="btn next">
                    {{
                        currentStep === tutorials.length - 1
                            ? "Start Adventure"
                            : "Next"
                    }}
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.tutorial-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    backdrop-filter: blur(4px);
}

.tutorial-modal {
    width: 700px;
    max-width: 95%;
    background: #141414;
    border: 2px solid #3d3d3d;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.6);
    animation: fadeIn 0.3s ease;
}

.tutorial-image {
    width: 100%;
    height: 320px;
    object-fit: cover;
    border-bottom: 2px solid #2b2b2b;
}

.tutorial-content {
    padding: 24px;
    color: white;
}

.tutorial-step {
    color: #9ca3af;
    margin-bottom: 10px;
    font-size: 14px;
}

.tutorial-content h2 {
    font-size: 28px;
    margin-bottom: 12px;
}

.tutorial-content p {
    color: #d1d5db;
    line-height: 1.7;
    font-size: 16px;
}

.tutorial-buttons {
    display: flex;
    justify-content: space-between;
    padding: 20px 24px;
    background: #0f0f0f;
    border-top: 1px solid #2a2a2a;
}

.btn {
    padding: 12px 22px;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
}

.prev {
    background: #2f2f2f;
    color: white;
}

.prev:hover {
    background: #444;
}

.next {
    background: #7c3aed;
    color: white;
}

.next:hover {
    background: #6d28d9;
}

.btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.96);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
