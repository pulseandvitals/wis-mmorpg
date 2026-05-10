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
</template>

<script setup>
const menuItems = [
    { id: 1, label: "Inventory", icon: "🎒" },
    { id: 2, label: "Gears", icon: "✨" },
    { id: 3, label: "Skills", icon: "✨" },
    { id: 4, label: "Ranking", icon: "🏆" },
    { id: 5, label: "Discord", icon: "💬" },
];

const handleMenuClick = (item) => {
    console.log(item.label);

    if (item.id === 5) {
        window.open("https://discord.com", "_blank");
    }
};
</script>

<style scoped>
.hud-menu {
    position: absolute;
    top: 12px;
    right: 12px;

    display: flex;
    flex-direction: column;
    gap: 6px;

    z-index: 9999;
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

    font-size: 6px;
    font-family: "Press Start 2P", cursive;

    cursor: pointer;

    transition: all 0.15s ease;
}

.hud-btn:hover {
    background: rgba(251, 191, 36, 0.15);
    transform: translateX(-2px);
}

.hud-icon {
    font-size: 10px;
}

.hud-label {
    white-space: nowrap;
}
</style>
